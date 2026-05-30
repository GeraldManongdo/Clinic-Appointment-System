<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $settings = new SiteSettingModel();
        $serviceModel = new ServiceModel();
        $testimonialModel = new TestimonialModel();
        $data = [
            'site' => $settings->all(),
            'services' => $serviceModel->all(6, 0),
            'serviceCount' => $serviceModel->count(),
            'testimonials' => $testimonialModel->approved(),
            'user' => Auth::user(),
        ];
        $this->render('home', $data);
    }
}
