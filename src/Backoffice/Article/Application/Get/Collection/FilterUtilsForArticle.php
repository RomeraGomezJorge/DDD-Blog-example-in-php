<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Get\Collection;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Infrastructure\Utils\FilterUtilsForFieldThatNotBelongToAnEntity;
	
	final class FilterUtilsForArticle
	{
		const FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM = ['category'];
		private CategoryFinder $finderCategory;
		
		public function __construct(CategoryRepository $categoryRepository)
		{
			$this->finderCategory = new CategoryFinder($categoryRepository);
		}
		
		public function getCategoryFromFilterOrNull(array $filters): ?Category
		{
			foreach (self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM as $fieldName) {
				if (!FilterUtilsForFieldThatNotBelongToAnEntity::isFieldNameDefineAsFilter($filters, $fieldName)) {
					return null;
				};
				
				$categoryId = FilterUtilsForFieldThatNotBelongToAnEntity::getValueFromFilters(
					$filters,
					$fieldName
				);
			}
			
			return $this->finderCategory->__invoke($categoryId);
		}
		
		public function removeFiltersThatNotBelongToEntity($filters): array
		{
			return FilterUtilsForFieldThatNotBelongToAnEntity::removeFilterEqualsTo(
				self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM,
				$filters
			);
		}
	}