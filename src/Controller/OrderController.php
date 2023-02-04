<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\Model\ItemModel;
use App\Dto\Response\Transformer\OrderResponseDtoTransformer;
use App\Entity\Item;
use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class OrderController extends AbstractAPIController implements APICRUDInterface
{
    private OrderResponseDtoTransformer $orderResponseDtoTransformer;

    public function __construct(OrderResponseDtoTransformer $orderResponseDtoTransformer)
    {
        $this->orderResponseDtoTransformer = $orderResponseDtoTransformer;
    }

    #[Route('/order', name: 'order_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $orders = $doctrine->getRepository(Order::class)->findAllOrderById();
        $dto = $this->orderResponseDtoTransformer->transformFromObjects($orders);
        return $this->json($dto);
    }

    #[Route('/order/{id}', name: 'order_show', methods: 'GET')]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $order = $doctrine->getRepository(Order::class)->find($id);
        $dto = $this->orderResponseDtoTransformer->transformFromObject($order);
        return $this->json($dto);
    }

    /**
     * @throws \Exception
     */
    #[Route('/order', name:'order_new', methods: 'POST')]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse|Response
    {
        //validation check
        $form = $this->buildForm(\OrderType::class);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if(!$form->isSubmitted() || !$form->isValid()){
                return $this->respond($form, Response::HTTP_BAD_REQUEST);
            }
        }else{
            return $this->json(null, Response::HTTP_BAD_REQUEST);
        }

        $orderModel = $form->getData();
        $productRepository = new ProductRepository($doctrine);
        $customerRepository = new CustomerRepository($doctrine);
        $entityManager = $doctrine->getManager();

        $orderInstance = new Order();
        $orderInstance->setCustomer($customerRepository->find($orderModel->id));

        //add items
        /**
         * @var ItemModel $item
         */
        foreach ($orderModel->items as $item){
            $itemInstance = new Item();
            $productInstance = $productRepository->find($item->productId);

            //check stock
            if(!$productInstance->isStockAvailable($item->quantity)){
                //todo: might want to use a factory for returning messages
                return new JsonResponse(
                    json_encode([
                        "code" => Response::HTTP_NOT_FOUND,
                        "message" => 'Not enough stock for '. $productInstance->getName() .'. '.
                            'Stock available: '.$productInstance->getStock().'. You requested: '.$item->quantity,
                        "status" => 'error'
                    ]),
                    Response::HTTP_OK,
                    [],
                    true
                );
            }

            $itemInstance->setProduct($productInstance)
                        ->setQuantity($item->quantity)
                        ->setUnitPrice($item->unitPrice)
                        ->setTotal($item->total);

            $productInstance->setStock($productInstance->getStock() - $item->quantity);

            $entityManager->persist($itemInstance);
            $entityManager->persist($productInstance);
            $orderInstance->addItem($itemInstance);
            $orderInstance->setTotal(
                $orderInstance->calculateTotalPrice($itemInstance->getQuantity(), $itemInstance->getUnitPrice())
            );
        }

        $entityManager->persist($orderInstance);
        $entityManager->flush();

        //returning successfully added message
        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'Created a new order successfully for '.$orderInstance->getCustomer()->getName(). ".",
                "status" => 'success'
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/order/{id}', name: 'order_update', methods: 'PUT')]
    public function update(int $id, ManagerRegistry $doctrine, Request $request)
    {
        // TODO: Implement update() method.
    }

    #[Route('/order/{id}', name: 'order_delete', methods: 'DELETE')]
    public function delete(int $id, ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $orderRepository = new OrderRepository($doctrine);
        $order = $orderRepository->find($id);
        if(!$order){
            //todo: might want to use a factory for returning messages
            return new JsonResponse(
                json_encode([
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => 'No order was found for id: '.$id. ".",
                    "status" => 'error'
                ]),
                Response::HTTP_OK,
                [],
                true
            );
        }

        $entityManager = $doctrine->getManager();
        $productRepository = new ProductRepository($doctrine);

        $itemInstances = $order->getItems();
        /**
         * @var Item $item
         */
        foreach ($itemInstances as $item){
            //change product stock data
            $product = $productRepository->find($item->getProduct()->getId());
            $product->setStock($product->getStock() + $item->getQuantity());
            $order->removeItem($item);

            $entityManager->persist($product);
            $entityManager->persist($order);
        }

        //remove order (orderId is for returning message below)
        $orderId = $order->getId();
        $orderRepository->remove($order, true);
        $entityManager->flush();

        //returning successfully deleted message
        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'Removed an order successfully with id: '.$orderId. ".",
                "status" => 'success'
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
