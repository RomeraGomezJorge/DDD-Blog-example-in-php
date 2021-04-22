<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Get\Collection;
	
	use App\Backoffice\Role\Application\Get\RoleFinder;
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Shared\Infrastructure\Utils\FilterUtilsForFieldThatNotBelongToAnEntity;
	
	final class FilterUtilsForUser
	{
		const FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM = ['role'];
		private RoleFinder $roleFinder;
		
		public function __construct(RoleRepository $roleRepository)
		{
			$this->roleFinder = new RoleFinder($roleRepository);
		}
		
		public function getRoleFromFilterOrNull(array $filters): ?Role
		{
			foreach (self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM as $fieldName) {
				if (!FilterUtilsForFieldThatNotBelongToAnEntity::isFieldNameDefineAsFilter($filters, $fieldName)) {
					return null;
				};
				
				$roleId = FilterUtilsForFieldThatNotBelongToAnEntity::getValueFromFilters($filters, $fieldName);
			}
			
			return $this->roleFinder->__invoke($roleId);
		}
		
		public function removeFiltersThatNotBelongToPostEntity($filters): array
		{
			return FilterUtilsForFieldThatNotBelongToAnEntity::removeFilterEqualsTo(self::FIELDS_NAME_THAT_DOES_NOT_BELONG_TO_THE_ENTITY_IN_THE_FILTER_FORM,
				$filters,);
		}
	}