<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	final class FilterUtilsForFieldThatNotBelongToAnEntity
	{
		public static function isFieldNameDefineAsFilter(array $filters, string $fieldNameToFind): bool
		{
			/* gets an array with all the fields that can be use as filters */
			$fieldsFoundInFilters = array_column($filters, 'field');
			return in_array($fieldNameToFind, $fieldsFoundInFilters);
		}
		
		public static function getValueFromFilters(array $filters, string $fieldName): ?string
		{
			$fieldsInFilters = array_column($filters, 'field');
			
			$valuesInFilters = array_column($filters, 'value');
			
			if (!in_array($fieldName, $fieldsInFilters)) {
				return null;
			}
			
			$keyWithParentId = array_search($fieldName, $fieldsInFilters);
			
			if ($keyWithParentId === false) {
				return null;
			}
			
			if (!isset($valuesInFilters[$keyWithParentId])) {
				return null;
			}
			
			return $valuesInFilters[$keyWithParentId];
		}
		
		public static function removeFilterEqualsTo(array $fieldsNamesToRemove, array $filters): array
		{
			/* gets an array with all the fields that can be use as filters */
			$fieldsFoundInFilters = array_column($filters, 'field');
			
			/* If the field to be remove is not between the found fields, it returns the filters as it was received.*/
			foreach ($fieldsFoundInFilters as $fieldFound) {
				if (!in_array($fieldFound, $fieldsNamesToRemove)) {
					continue;
				}
				
				$fieldNameToRemove = $fieldFound;
				
				/* get the position of the field to remove */
				$keyToRemove = array_search($fieldNameToRemove, $fieldsFoundInFilters);
				
				/* check if it could get the position of the field to remove  */
				if ($keyToRemove === false) {
					return $filters;
				}
				
				/* removes the field you want to remove from the filters */
				unset($filters[$keyToRemove]);
			}
			
			return $filters;
		}
	}