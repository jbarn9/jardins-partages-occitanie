<?php

namespace App\Controller\API;

use App\Storage\UserUploadStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TinyMceUploadController extends AbstractController
{
    const MAX_FILESIZE = 20000000; // 20 MB

    public function __construct(private readonly ParameterBagInterface $params) {}

    #[Route('/api/tinymce-upload/image', name: 'api_tinymce_upload_image', methods: ['POST'])]
    public function upload(Request $request): Response
    {
        // @TODO: Set your own domain(s) in `$allowedOrigins`
        $allowedOrigins = ["https://localhost:8000", "https://127.0.0.1:8000"];
        $origin = $request->server->get('HTTP_ORIGIN');
        $temp = current($_FILES);
        // same-origin requests won't set an origin. If the origin is set, it must be valid.
        if ($origin && !in_array($origin, $allowedOrigins)) {
            return new Response("You do not have access to this resource.", 403);
        }

        // Don't attempt to process the upload on an OPTIONS request
        if ($request->isMethod("OPTIONS")) {
            return new Response("", 200, ["Access-Control-Allow-Methods" => "POST, OPTIONS"]);
        }

        /** @var UploadedFile|null */
        $file = $request->files->get("file");

        if (!$file) {
            return new Response("Missing file.", 400);
        }

        if ($file->getSize() > self::MAX_FILESIZE) {
            return new Response("Your file is too big. Maximum size: " . (self::MAX_FILESIZE / 1000000) . "MB", 400);
        }

        if (!str_starts_with($file->getMimeType(), "image/")) {
            return new Response("Provided file is not an image.", 400);
        }

        /** 
         * @TODO: Replace this next line with your own file upload/save process. 
         * The $fileUrl variable should contain the publicly accessible URL of
         * the file/image.
         */
        $extension = pathinfo($temp['name'], PATHINFO_EXTENSION);
        $imageFolder = 'assets/uploads/posts/';
        $filetowrite = $imageFolder . $temp['name'];
        $fileUrl = $this->params->get('uploads_directory') . 'posts/' . uniqid() . "." . $extension;
        move_uploaded_file($temp['tmp_name'], $fileUrl);

        return new JsonResponse(
            ["location" => $filetowrite],
            200,
            [
                "Access-Control-Allow-Origin" => $origin,
                "Access-Control-Allow-Credentials" => true,
                "P3P" => 'CP="There is no P3P policy."',
            ],
        );
    }
}
