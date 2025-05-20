<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OCRController extends Controller
{
    public function extractText_old(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        $image = $request->file('image');
        $apiKey = env('TOGETHER_API_KEY');
        $model = 'meta-llama/Llama-Vision-Free';

        // Read and encode image
        $base64Image = base64_encode(file_get_contents($image));
        $imageDataUrl = 'data:image/' . $image->getClientOriginalExtension() . ';base64,' . $base64Image;

        // Build system prompt
        $systemPrompt = <<<EOT
Convert the provided image into Markdown format. Ensure that all content from the page is included, such as headers, footers, subtexts, images (with alt text if possible), tables, and any other elements.

Requirements:
- Output Only Markdown: Return solely the Markdown content without any additional explanations or comments.
- No Delimiters: Do not use code fences or delimiters like ```markdown.
- Complete Content: Do not omit any part of the page, including headers, footers, and subtext.
EOT;

        // Send request to Together.ai
        $response = Http::withToken($apiKey)->post('https://api.together.xyz/v1/chat/completions', [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $systemPrompt],
                        ['type' => 'image_url', 'image_url' => ['url' => $imageDataUrl]],
                    ],
                ],
            ],
        ]);

        if ($response->successful()) {
            // return response()->json([
            //     'markdown' => $response['choices'][0]['message']['content'] ?? '(No result)',
            // ]);

            $markdown = $response['choices'][0]['message']['content'] ?? '(No result)';
            return view('result', ['markdown' => $markdown]);
        }

        return response()->json(['error' => 'OCR failed', 'details' => $response->body()], 500);
    }
}

