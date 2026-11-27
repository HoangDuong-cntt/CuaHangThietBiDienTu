<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiAssistantController extends Controller
{
    public function __construct(
        protected GeminiService $geminiService
    ) {}

    /**
     * API Chatbot tương tác thời gian thực
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'session_id' => ['nullable', 'string', 'max:255'],
        ]);

        $sessionId = $validated['session_id'] ?? ($request->hasSession() ? $request->session()->getId() : (string) Str::uuid());
        $userId = $request->user()?->id;

        $product = ! empty($validated['product_id'])
            ? Product::find($validated['product_id'])
            : null;

        $reply = $this->geminiService->chatWithContext(
            userMessage: $validated['message'],
            sessionId: $sessionId,
            userId: $userId,
            product: $product
        );

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'session_id' => $sessionId,
        ]);
    }

    /**
     * API tạo tóm tắt thông số sản phẩm (AI Summary)
     */
    public function summary(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        $summary = $this->geminiService->generateProductSummary($product);

        $product->update([
            'ai_summary' => $summary,
        ]);

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    /**
     * API tự động sinh bài viết mô tả sản phẩm (Dành cho Admin)
     */
    public function generateDescription(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'specs' => ['nullable', 'array'],
        ]);

        $description = $this->geminiService->generateProductDescription(
            productName: $validated['product_name'],
            brand: $validated['brand'] ?? '',
            specs: $validated['specs'] ?? []
        );

        return response()->json([
            'success' => true,
            'description' => $description,
        ]);
    }
}
