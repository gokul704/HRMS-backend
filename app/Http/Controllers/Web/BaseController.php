<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    /**
     * Safely render a view with error handling
     */
    protected function safeView($view, $data = [])
    {
        try {
            if (View::exists($view)) {
                return view($view, $data);
            } else {
                Log::warning("View not found: {$view}");

                // Create a basic view automatically
                $this->createBasicView($view);

                return view($view, $data);
            }
        } catch (\Exception $e) {
            Log::error("View rendering error for {$view}: " . $e->getMessage());

            return view('errors.500', [
                'error' => $e->getMessage(),
                'view' => $view
            ]);
        }
    }

    /**
     * Create a basic view if it doesn't exist
     */
    private function createBasicView($viewName)
    {
        $viewPath = resource_path('views/' . str_replace('.', '/', $viewName) . '.blade.php');
        $directory = dirname($viewPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!file_exists($viewPath)) {
            $title = ucwords(str_replace(['.', '-', '_'], ' ', $viewName));

            $content = <<<HTML
@extends('layouts.app')

@section('title', '{$title} - HRMS')

@section('page-title', '{$title}')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>
            {$title}
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h6>View Under Construction</h6>
            <p>This view ({$viewName}) is being generated automatically. Please customize it according to your needs.</p>
        </div>

        <div class="mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Go Back
            </a>
        </div>
    </div>
</div>
@endsection
HTML;

            file_put_contents($viewPath, $content);
            Log::info("Created missing view: {$viewName}");
        }
    }
}
