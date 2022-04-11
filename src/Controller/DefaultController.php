<?php

namespace App\Controller;

use App\Entity\Vehicles;
use App\Repository\VehiclesRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $vehicleRepository;
    private $paginator;

    public function __construct(VehiclesRepository $vehicleRepository, PaginatorInterface $paginator)
    {
        $this->vehicleRepository = $vehicleRepository;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="app_home")
     * @return Response
     */
    public function appDefaultAction(Request $request): Response
    {
        $params = $this->getParamsForQuery($request);

        $pagination = $this->paginator->paginate(
            $this->vehicleRepository->getQueryBuilderForPagination($params),
            $params['page'],
            11
        );

        $filterData = $this->getDataForFilter();

        return $this->render('app/index.html.twig', compact('filterData', 'pagination', 'params'));
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getDataForFilter(): array
    {
        return [
            'price' => [
                'min' => $this->vehicleRepository->getMin('price'),
                'max' => $this->vehicleRepository->getMax('price'),
            ],
            'priceMonthly' => [
                'min' => $this->vehicleRepository->getMin('price_monthly'),
                'max' => $this->vehicleRepository->getMax('price_monthly'),
            ],
            'models' => $this->vehicleRepository->getDictionary(Vehicles::VEHICLE_MODEL),
            'brands' => $this->vehicleRepository->getDictionary(Vehicles::VEHICLE_BRAND),
            'energies' => $this->vehicleRepository->getDictionary(Vehicles::VEHICLE_ENERGY),
        ];
    }

    public function getParamsForQuery(Request $request): array
    {
        $price = $request->get('price');
        $priceMonthly = $request->get('price-monthly');
        return [
            'selectedBrands' => is_array($request->get('brands')) ? $request->get('brands', []) : [],
            'selectedModels' => is_array($request->get('models')) ? $request->get('models') : [],
            'selectedEnergies' => is_array($request->get('energies')) ? $request->get('energies') : [],
            'priceSelected' => is_string($request->get('selected')) ? (int)$request->get('energies') : 1,
            'price' => [
                'min' => is_array($price) && isset($price['min']) && is_string($price['min']) ? $price['min'] : '',
                'max' => is_array($price) && isset($price['max']) && is_string($price['max']) ? $price['max'] : '',
            ],
            'priceMonthly' => [
                'min' => is_array($priceMonthly) && isset($priceMonthly['min']) && is_string($priceMonthly['min']) ? $priceMonthly['min'] : '',
                'max' => is_array($priceMonthly) && isset($priceMonthly['max']) && is_string($priceMonthly['max']) ? $priceMonthly['max'] : '',
            ],
            'sorting' => is_string($request->get('sorting')) ? $request->get('sorting') : 'id',
            'direction' => is_string($request->get('direction')) ? $request->get('direction') : 'desc',
            'page' => is_string($request->get('page')) ? (int)$request->get('direction') : 1,
        ];
    }
}