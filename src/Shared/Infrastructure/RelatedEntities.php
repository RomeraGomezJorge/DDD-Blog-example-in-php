<?php
	
	namespace App\Shared\Infrastructure;
	
	use App\Backoffice\Author\Application\Get\Collection\AuthorsByCriteriaSearcher;
	use App\Backoffice\Category\Application\Get\Collection\CategoriesByCriteriaSearcher;
	use App\Backoffice\Category\Infrastructure\UserInterface\Web\CategoryFilterUtils;
	
	final class RelatedEntities
	{
		const SHOW_ALL_ITEMS = [];
		const SORT_A_LIST_BY_NAME = 'name';
		const SORT_A_LIST_BY_POSITION = 'position';
		const SORT_A_LIST_BY_FULLNAME = 'fullname';
		const SORT_A_LIST_ALPHABETICALLY = 'asc';
		const LIST_BEGIN_ON_0 = 0;
		const LIST_END_ON_1000 = 1000;
		private AuthorsByCriteriaSearcher    $authorsByCriteriaSearcher;
		private CategoriesByCriteriaSearcher   $categoriesByCriteriaSearcher;
		private ArticleMediaFilesByCriteriaSearcher $articleMediaFilesByCriteriaSearcher;
		
		public function __construct(
			AuthorsByCriteriaSearcher $bodyTypesByCriteriaSearcher,
			CategoriesByCriteriaSearcher $categoriesByCriteriaSearcher
		
		) {
			$this->authorsByCriteriaSearcher = $bodyTypesByCriteriaSearcher;
			$this->categoriesByCriteriaSearcher = $categoriesByCriteriaSearcher;
		}
		
		public function getAllAuthorsSortedAlphabetically(): array
		{
			return $this->authorsByCriteriaSearcher->__invoke(self::SHOW_ALL_ITEMS,
				self::SORT_A_LIST_BY_FULLNAME,
				self::SORT_A_LIST_ALPHABETICALLY,
				self::LIST_END_ON_1000,
				self::LIST_BEGIN_ON_0);
		}
		
		public function getAllParentCategoriesSortedAlphabetically(): array
		{
			return $this->categoriesByCriteriaSearcher->__invoke(
				CategoryFilterUtils::filterToDisplayParent(),
				self::SORT_A_LIST_BY_NAME,
				self::SORT_A_LIST_ALPHABETICALLY,
				self::LIST_END_ON_1000,
				self::LIST_BEGIN_ON_0);
		}
		
		public function getAllParentCategoriesSortedByPosition(): array
		{
			return $this->categoriesByCriteriaSearcher->__invoke(
				CategoryFilterUtils::filterToDisplayParent(),
				self::SORT_A_LIST_BY_POSITION,
				'asc',
				self::LIST_END_ON_1000,
				self::LIST_BEGIN_ON_0);
		}
		
		public function getCategoriesSortedByPosition(): array
		{
			return $this->categoriesByCriteriaSearcher->__invoke(
				self::SHOW_ALL_ITEMS,
				self::SORT_A_LIST_BY_POSITION,
				'asc',
				self::LIST_END_ON_1000,
				self::LIST_BEGIN_ON_0);
		}
	}