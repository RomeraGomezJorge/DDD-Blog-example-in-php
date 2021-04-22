<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Get\Collection;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Infrastructure\Utils\FilterUtilsForFieldThatNotBelongToAnEntity;
	use App\Shared\Infrastructure\Utils\StringUtils;
	use InvalidArgumentException;
	
	final class FilterUtilsForCategory
	{
		const FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM = ['parent_id'];
		const DEFAULT_VALUE_IN_FILTERS_TO_SHOW_NOT_PARENT_CATEGORIES = 'parent';
		private CategoryFinder $categoryFinder;
		
		public function __construct(CategoryRepository $repository)
		{
			$this->categoryFinder = new CategoryFinder($repository);
		}
		
		public function getParentFromFilterOrNull(array $filters): ?Category
		{
			foreach (self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM as $fieldName) {
				if ($this->isValidFieldName($fieldName)) {
					Throw new InvalidArgumentException('El valor ' . $fieldName . ' no puede ser defindo como un filtro de busqueda');
				}
				
				if (!FilterUtilsForFieldThatNotBelongToAnEntity::isFieldNameDefineAsFilter($filters, $fieldName)) {
					return null;
				};
				
				$categoryIdFoundInFilters = FilterUtilsForFieldThatNotBelongToAnEntity::getValueFromFilters($filters,
					$fieldName);
			}
			
			return $this->categoryFinder->__invoke($categoryIdFoundInFilters);
		}
		
		private function isValidFieldName($fieldName): bool
		{
			return StringUtils::equals($fieldName, self::DEFAULT_VALUE_IN_FILTERS_TO_SHOW_NOT_PARENT_CATEGORIES);
		}
		
		public function removeFiltersThatNotBelongToEntity($filters): array
		{
			return FilterUtilsForFieldThatNotBelongToAnEntity::removeFilterEqualsTo(
				self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM,
				$filters
			);
		}
	}