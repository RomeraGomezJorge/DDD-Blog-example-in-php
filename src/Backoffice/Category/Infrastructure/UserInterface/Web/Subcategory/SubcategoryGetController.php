<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Backoffice\Category\Application\Get\Collection\CategoriesByCriteriaSearcher;
	use App\Backoffice\Category\Application\Get\Collection\CategoryByCriteriaCounter;
	use App\Backoffice\Category\Infrastructure\UserInterface\Web\CategoryFilterUtils;
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\WebController;
	use App\Shared\Infrastructure\UserInterface\Web\TwigTemplateGlobalConstants;
	use App\Shared\Infrastructure\Utils\FilterUtils;
	use App\Shared\Infrastructure\Utils\NextPage;
	use App\Shared\Infrastructure\Utils\OffsetPaginationUtil;
	use App\Shared\Infrastructure\Utils\PreviousPage;
	use App\Shared\Infrastructure\Utils\SortUtils;
	use App\Shared\Infrastructure\Utils\TotalNumberOfPagesUtil;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class SubcategoryGetController extends WebController
	{
		public function __invoke(
			Request $request,
			CategoriesByCriteriaSearcher $itemsByCriteriaSearcher,
			CategoryByCriteriaCounter $counter,
			RelatedEntities $relatedEntities
		): Response {
			$orderBy = $request->get('orderBy');
			
			$order = $request->get('order');
			
			$page = (int)$request->get('page');
			
			$limit = (int)$request->get('limit');
			
			$filters = FilterUtils::getFiltersOrEmpyArray($request->get('filters'));
			
			$filters = CategoryFilterUtils::appendFilterToDisplayChildrenCategories($filters);
			
			$categories = $itemsByCriteriaSearcher->__invoke($filters, $order, $orderBy, $limit,
				OffsetPaginationUtil::calculate($limit, $page));
			
			$totalItem = $counter->__invoke($filters, $order, $orderBy, $limit,
				OffsetPaginationUtil::calculate($limit, $page));
			
			$parent_categories = $relatedEntities->getAllParentCategoriesSortedAlphabetically();
			
			$totalNumberOfPages = TotalNumberOfPagesUtil::calculate($page, $limit, $totalItem);
			
			return $this->render(TwigTemplateConstants::LIST_FILE_PATH, [
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'edit_path' => TwigTemplateConstants::EDIT_PATH,
				'add_path' => TwigTemplateConstants::ADD_PATH,
				'delete_path' => TwigTemplateConstants::DELETE_PATH,
				'delete_confirmation_modal_path' => TwigTemplateGlobalConstants::DELETE_CONFIRMATION_MODAL_PATH,
				'orderBy' => $orderBy,
				'order' => $order,
				'limit' => $limit,
				'filters' => $request->get('filters'),
				'toggleSort' => SortUtils::toggle($orderBy),
				'currentPage' => $page,
				'nextPage' => NextPage::calculate($page, $totalNumberOfPages),
				'previousPage' => PreviousPage::calculate($page),
				'totalPage' => $totalNumberOfPages,
				'totalItem' => $totalItem,
				'categories' => $categories,
				'parent_categories' => $parent_categories
			]);
		}
	}
