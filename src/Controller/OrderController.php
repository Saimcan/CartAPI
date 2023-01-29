<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Order;
use App\Repository\CustomerRepository;
use App\Repository\ItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class OrderController extends AbstractAPIController implements APICRUDInterface
{
    #[Route('/order', name: 'order_list', methods: 'GET')]
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $orderRepository = new OrderRepository($doctrine);
        $orders = $orderRepository->findAll();
        $data = [];

        /**
         * @var $order Order
         */
        foreach ($orders as $order){
            $data[] = [
                'id' => $order->getId(),
                'customerId' => $order->getCustomer()->getId(),
                'items' => $order->getItems(),
                'total' => $order->getTotal()
            ];
        }

        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'All orders are listed below:',
                "status" => 'success',
                "data" => $data
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/order/{id}', name: 'order_show', methods: 'GET')]
    public function show(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $orderRepository = new OrderRepository($doctrine);
        $order = $orderRepository->find($id);
        if(!$order){
            return $this->json('No order found for id: ' . $id, 404);
        }

        /**
         * @var $order Order
         */
        $data = [
            'id' => $order->getId(),
            'customerId' => $order->getCustomer()->getId(),
            'items' => $order->getItems(),
            'total' => $order->getTotal()
        ];

        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'Order details are shown below:',
                "status" => 'success',
                "data" => $data
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/order', name:'order_new', methods: 'POST')]
    public function create(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): JsonResponse|Response
    {
        $customerRepository = new CustomerRepository($doctrine);
        $customer = $customerRepository->find($request->get('customerId'));
        $order = new Order();
        $order->setCustomer($customer);
        $order->setItems($request->get('items'));

        $entityManager = $doctrine->getManager();
        $productRepository = new ProductRepository($doctrine);
        $itemValidationErrorMessages = [];
        $allValidationErrorMessages = [];
        $totalPrice = 0;

        foreach ($order->getItems() as $item){
            $itemInstance = new Item();
            $product = $productRepository->find($item["productId"]);
            $itemInstance->setProduct($product)
                ->setQuantity($item["quantity"])
                ->setUnitPrice($product->getPrice())
                ->setTotal($product->getPrice() * $itemInstance->getQuantity());
            $totalPrice += floatval($product->getPrice());

            //item validation (works for current one, bypasses previous ones), might want to refactor
            $itemValidationErrorMessages = $validator->validate($itemInstance);

            //update stock and persist data
            $product->setStock($product->getStock() - $itemInstance->getQuantity());
            $entityManager->persist($product);
            $entityManager->persist($itemInstance);
        }

        $order->setTotal((string)$totalPrice);

        //handling error messages
        //order
        $orderValidationErrorMessages = $validator->validate($order);
        if(count($orderValidationErrorMessages) > 0){
            foreach ($orderValidationErrorMessages as $errorMessage){
                $allValidationErrorMessages[] = $errorMessage->getMessage()." : ".$errorMessage->getPropertyPath();
            }
        }
        //item
        if(count($itemValidationErrorMessages) > 0){
            foreach ($itemValidationErrorMessages as $errorMessage){
                $allValidationErrorMessages[] = $errorMessage->getMessage()." : ".$errorMessage->getPropertyPath();
            }
        }
        //all validation error messages check
        if(count($allValidationErrorMessages) > 0){
            return $this->response($allValidationErrorMessages, Response::HTTP_BAD_REQUEST);
        }

        //insert into db
        $entityManager->persist($order);
        $entityManager->flush();

        //returning successfully added message
        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'Created a new order successfully for '.$order->getCustomer()->getName(). ".",
                "status" => 'success',
                "data" => $order->getItems()
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/order/{id}', name: 'order_update', methods: 'PUT')]
    public function update(int $id, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator)
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
        $itemRepository = new ItemRepository($doctrine);

        $itemInstances = $itemRepository->findByOrderPlaced($order);
        /**
         * @var Item $item
         */
        foreach ($itemInstances as $item){
            //change product stock data
            $product = $productRepository->find($item->getProduct()->getId());
            $product->setStock($product->getStock() + $item->getQuantity());
            $entityManager->persist($product);
        }

        //remove items
        $itemRepository->removeByOrderPlaced($order, true);
        //remove order (orderId is for returning message below)
        $orderId = $order->getId();
        $orderRepository->remove($order, true);
        //update product stock data
        $entityManager->flush();

        //returning successfully deleted message
        //todo: might want to use a factory for returning messages
        return new JsonResponse(
            json_encode([
                "code" => Response::HTTP_OK,
                "message" => 'Removed an order successfully with id :'.$orderId. ".",
                "status" => 'success'
            ]),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
