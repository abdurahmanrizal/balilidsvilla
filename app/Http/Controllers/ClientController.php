<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Description;
use App\Models\Resort;
use App\Models\Package;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\SocialMedia;
use App\Models\Banner;
use App\Models\Testimonial;
class ClientController extends Controller
{
    public function index()
    {
       return view('client')->with([
           'description' => $this->getDescription(),
           'villas'      => $this->getVillas(),
           'activities'  => $this->getActivity(),
           'packages'    => $this->getPackage(),
           'blogs'       => $this->getBlog(),
           'galleries'   => $this->getGallery(),
           'social_medias' => $this->socialMedia(),
           'banners'     => $this->getBanner(),
           'testimonials' => $this->getTestimonial()
       ]);
    }

    private function getDescription()
    {
        $description = Description::first();
        return !empty($description) ? $description->description_company : 'empty';
    }
    private function getVillas()
    {
        $villas = Resort::with(['resort_gallery'])->whereHas('category', function($query) {
            $query->where('name','villa');
        })->limit(3)->get();
        return !empty($villas) ? $villas : 'empty';
    }
    private function getActivity()
    {
        $activities = Resort::with(['resort_gallery'])->whereHas('category', function($query) {
            $query->where('name','activity');
        })->limit(3)->get();
        return !empty($activities) ? $activities : 'empty';
    }
    private function getPackage()
    {
        $packages = Package::with(['package_gallery','resort_items'])->get();
        return !empty($packages) ? $packages : 'empty';
    }
    private function getBlog()
    {
        $blogs = Blog::limit(5)->orderBy('id','desc')->get();
        return !empty($blogs) ? $blogs : 'empty';
    }
    private function getGallery()
    {
        $galleries = Gallery::limit(4)->orderBy('id','desc')->get();
        return !empty($galleries) ? $galleries : 'empty';
    }
    private function socialMedia()
    {
        $social_medias = SocialMedia::all();
        return $social_medias;
    }
    private function getBanner()
    {
        $banners = Banner::all();
        return $banners;
    }
    private function getTestimonial()
    {
        $testimonials = Testimonial::limit(3)->get();
        return $testimonials;
    }
}
