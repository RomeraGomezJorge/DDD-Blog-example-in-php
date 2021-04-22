<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	final class FilterUtils
	{
		const EMPTY_FILTERS = [];
		
		public static function getFiltersOrEmpyArray(?string $stringOfFilterArrayStruct): array
		{
			if ($stringOfFilterArrayStruct === null) {
				return self::EMPTY_FILTERS;
			}
			
			$filters = self::convertStringToArray($stringOfFilterArrayStruct);
			
			return isset($filters)
				? $filters
				: self::EMPTY_FILTERS;
		}
		
		private static function convertStringToArray(string $stringWithArrayStruct)
		{
			/* Analiza str como si fuera un string de consulta pasado por medio de un URL y establece variables
			en el ámbito actual. En el caso de los filtros en la url saldria algo similar a esto "filters%5B0%5D%5Bfield%5D=vehicl"
			por lo tanto se crea un variable de ambito local $filters
			*/
			parse_str($stringWithArrayStruct);
			
			return $filters;
		}
	}
	