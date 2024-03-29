<?php
namespace App\Http\Controllers;
use App\Image;
use App\Http\Requests\StoreImage;
use Illuminate\Support\Facades\Storage;
class ImageController extends Controller
{
    private $image;
    public function __construct(Image $image)
    {
        $this->image = $image;
    }
    public function getImages()
    {
        return view('images')->with('images', auth()->user()->images);
    }
    public function postUpload(StoreImage $request)
    {
        $path = Storage::disk('s3')->put('images/originals2', $request->file, 'public'); //Sube

        $request->merge([
            'size' => $request->file->getClientSize(),
            'path' => $path
        ]);
        $this->image->create($request->only('path', 'title', 'size'));
        $domain = 'https://wellnesspal.s3.us-east-2.amazonaws.com/';
        $fullpath = $domain.$path;
        return "<img src='$fullpath'>";
        return back()->with('success', 'Image Successfully Saved');
    }
}
