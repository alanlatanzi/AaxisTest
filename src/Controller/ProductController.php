<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/product/load", methods={"POST"})
     */
    public function loadRecords(Request $request): JsonResponse
    {
        try {
            if ($this->validateToken($request)->getStatusCode() == 200) {
                $data = json_decode($request->getContent(), true);

                if (empty($data)) {
                    throw new \Exception('No data sent!');
                }

                $existing_skus = [];

                $entityManager = $this->getDoctrine()->getManager();

                foreach ($data as $record) {

                    $sku = $record['sku'];
                    $product = $entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);
                    if($product) {
                        $existing_skus[] = $sku;
                    }
                    else{
                        $product = new Product();
                        $product->setSku($record['sku']);
                        $product->setProductName($record['product_name']);
                        $product->setDescription($record['description']);
                        $entityManager->persist($product);
                    }
                }

                $entityManager->flush();

                return $this->json([
                    'message' => 'Records loaded!',
                    'already_exist' => implode(', ', $existing_skus),
                    'data' => $data
                ]);
            } else {
                return $this->json([
                    'message' => 'Invalid token'
                ]);
            }
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    /**
     * @Route("/api/product/update", methods={"PUT"})
     */
    public function updateRecords(Request $request): JsonResponse
    {
        try {
            if ($this->validateToken($request)->getStatusCode() == 200) {
                $data = json_decode($request->getContent(), true);

                if (empty($data)) {
                    throw new \Exception('No data sent!');
                }

                $entityManager = $this->getDoctrine()->getManager();
                $sku_not_found = [];

                foreach ($data as $record) {

                    $sku = $record['sku'];
                    $product = $entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);

                    if (!$product) {
                        $sku_not_found[] = $sku;
                    } else {
                        $product->setProductName($record['product_name']);
                        $product->setDescription($record['description']);
                        $entityManager->persist($product);
                    }
                }

                $entityManager->flush();

                return $this->json([
                    'message' => 'Records updated!',
                    'not_found' => implode(', ', $sku_not_found),
                    'data' => $data
                ]);
            } else {
                return $this->json([
                    'message' => 'Invalid token'
                ]);
            }
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    /**
     * @Route("/api/products", methods={"GET"})
     */
    public function listProducts(Request $request): JsonResponse
    {
        try {
            if ($this->validateToken($request)->getStatusCode() == 200) {
                $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
                $message = $products ? 'Products found!' : 'No products found!';
                $products_list = [];
                foreach ($products as $product) {
                    $products_list[] = [
                        'id' => $product->getId(),
                        'sku' => $product->getSku(),
                        'product_name' => $product->getProductName(),
                        'description' => $product->getDescription(),
                        'created_at' => $product->getCreatedAt(),
                        'update_at' => $product->getUpdateAt()
                    ];
                }
                return $this->json([
                    'message' => $message,
                    'data' => $products_list
                ]);
            } else {
                return $this->json([
                    'message' => 'Invalid token'
                ]);
            }
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function validateToken(Request $request): JsonResponse
    {
        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));
        $user = $this->getDoctrine()->getRepository(\App\Entity\User::class)->findOneBy(['token' => $token]);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], 404);
        }
        return new JsonResponse([
            'message' => 'User found'
        ], 200);
    }
}
