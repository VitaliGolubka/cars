<?php

namespace App\Controller;

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
        $vehicleQueryBuilder = $this->vehicleRepository->createQueryBuilder('v');

        $selectedBrands = $request->get('brands') ?? [];
        if ($selectedBrands) {
            $vehicleQueryBuilder->andWhere("v.brand IN(:brands)")
                ->setParameter('brands', array_values($selectedBrands));
        }

        $selectedModels = $request->get('models') ?? [];
        if ($selectedModels) {
            $vehicleQueryBuilder->andWhere("v.model IN(:models)")
                ->setParameter('models', array_values($selectedModels));
        }

        $selectedEnergies = $request->get('energies') ?? [];
        if ($selectedEnergies) {
            $vehicleQueryBuilder->andWhere("v.energy IN(:energies)")
                ->setParameter('energies', array_values($selectedEnergies));
        }

        $maxPrice = $this->vehicleRepository->getMaxPrice();
        $minPrice = $this->vehicleRepository->getMinPrice();
        $selectedMinPrice = $request->get('min-price');
        $selectedMaxPrice = $request->get('max-price');
        if ($selectedMinPrice) {
            $selectedMinPrice = (int)$selectedMinPrice;
            $selectedMaxPrice = (int)$selectedMaxPrice;
            $vehicleQueryBuilder->andwhere('v.price BETWEEN :minPrice AND :maxPrice')
                ->setParameter('minPrice', $selectedMinPrice)
                ->setParameter('maxPrice', $selectedMaxPrice);
        } else {
            $selectedMinPrice = $minPrice;
            $selectedMaxPrice = $maxPrice;
        }

        $maxPriceMonthly = $this->vehicleRepository->getMaxPriceMonthly();
        $minPriceMonthly = $this->vehicleRepository->getMinPriceMonthly();
        $selectedMinPriceMonthly = $request->get('min-price-monthly');
        $selectedMaxPriceMonthly = $request->get('max-price-monthly');
        if ($selectedMinPriceMonthly) {
            $selectedMinPriceMonthly = (int)$selectedMinPriceMonthly;
            $selectedMaxPriceMonthly = (int)$selectedMaxPriceMonthly;
            $vehicleQueryBuilder->andwhere('v.price_monthly BETWEEN :minPriceMonthly AND :maxPriceMonthly')
                ->setParameter('minPriceMonthly', $selectedMinPriceMonthly)
                ->setParameter('maxPriceMonthly', $selectedMaxPriceMonthly);
        } else {
            $selectedMinPriceMonthly = $minPriceMonthly;
            $selectedMaxPriceMonthly = $maxPriceMonthly;
        }

        $sort = $request->get('sorting') ?? 'id';
        $direction = $request->get('direction') ?? 'desc';
        $vehicleQueryBuilder->orderBy('v.' . $sort, $direction);

        $pagination = $this->paginator->paginate(
            $vehicleQueryBuilder,
            $request->query->getInt('page', 1),
            11
        );

        $models = $this->vehicleRepository->getModels();
        $brands = $this->vehicleRepository->getBrands();
        $energies = $this->vehicleRepository->getEnergy();

        $priceSelected = $request->get('price-selected') ?? 1;

        return $this->render('app/index.html.twig', [
            'pagination' => $pagination,
            'models' => $models,
            'selectedModels' => $selectedModels,
            'brands' => $brands,
            'selectedBrands' => $selectedBrands,
            'energies' => $energies,
            'selectedEnergies' => $selectedEnergies,
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice,
            'selectedMinPrice' => $selectedMinPrice,
            'selectedMaxPrice' => $selectedMaxPrice,
            'maxPriceMonthly' => $maxPriceMonthly,
            'minPriceMonthly' => $minPriceMonthly,
            'selectedMinPriceMonthly' => $selectedMinPriceMonthly,
            'selectedMaxPriceMonthly' => $selectedMaxPriceMonthly,
            'priceSelected' => $priceSelected,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }
}