<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index(CategoryService $categoryService): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'user'  => auth()->user(),
                'message' => null,
                'data' => $categoryService->all(),
                'errors' => null,
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.operation_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCategoryRequest $request, CategoryService $categoryService): JsonResponse
    {
        try {
            $category = $categoryService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => __('messages.created'),
                'data' => $category,
                'errors' => null,
            ], Response::HTTP_CREATED);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.create_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id, CategoryService $categoryService): JsonResponse
    {
        try {
            $category = $categoryService->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.not_found'),
                    'data' => null,
                    'errors' => null,
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'message' => null,
                'data' => $category->load(['parent', 'children']),
                'errors' => null,
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.operation_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCategoryRequest $request, int $id, CategoryService $categoryService): JsonResponse
    {
        try {
            $category = $categoryService->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.not_found'),
                    'data' => null,
                    'errors' => null,
                ], Response::HTTP_NOT_FOUND);
            }

            $categoryService->update($category, $request->validated());

            return response()->json([
                'success' => true,
                'message' => __('messages.updated'),
                'data' => $category->fresh()->load(['parent', 'children']),
                'errors' => null,
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.update_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id, CategoryService $categoryService): JsonResponse
    {
        try {
            $category = $categoryService->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.not_found'),
                    'data' => null,
                    'errors' => null,
                ], Response::HTTP_NOT_FOUND);
            }

            $categoryService->delete($category);

            return response()->json([
                'success' => true,
                'message' => __('messages.deleted'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.delete_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}