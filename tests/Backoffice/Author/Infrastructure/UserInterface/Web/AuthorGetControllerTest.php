<?php
	
	namespace App\Tests\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Author\AuthorInfrastructureTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	use Symfony\Bundle\FrameworkBundle\KernelBrowser;
	
	final class AuthorGetControllerTest extends AuthorInfrastructureTestCase
	{
		private Author $author;
		private Uuid $id;
		/**
		 * @var KernelBrowser
		 */
		private $client;
		
		/** @test */
		public function it_should_find_author()
		{
			$anotherAuthor = AuthorMother::random();
			$someOtherAuthor = AuthorMother::random();
			$this->repository()->save($this->author);
			$this->repository()->save($anotherAuthor);
			$this->repository()->save($someOtherAuthor);
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->shouldFindOnThePage(
				$this->client,
				$this->author->fullname()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$anotherAuthor->fullname()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$someOtherAuthor->fullname()
			);
		}
		
		/** @test */
		public function it_should_show_interface_to_create_an_author()
		{
			$crawler = $this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a#createItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_CREATE_ITEMS);
		}
		
		/** @test
		 * Solo se comprueba la url con un filtro ya aplicado porque el envio de los filtros se realiza con javascript
		 */
		public function it_should_find_author_by_criteria()
		{
			$anotherAuthor = AuthorMother::random();
			$this->repository()->save($this->author);
			$this->repository()->save($anotherAuthor);
			
			$filterByField = 'fullname';
			$this->isOnPage(
				$this->client,
				self::LIST_ITEMS_PATH . '/page-1/order-createAt-desc/rows_per_page-10/filters%5B0%5D%5Bfield%5D=' . $filterByField . '&filters%5B0%5D%5Boperator%5D=%3D&filters%5B0%5D%5Bvalue%5D=' . $anotherAuthor->fullname()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$anotherAuthor->fullname()
			);
		}
		
		/** @test */
		public function it_should_show_interface_to_edit_an_author()
		{
			$this->repository()->save($this->author);
			
			$crawler = $this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a.editItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_UPDATE_ITEMS);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->author = AuthorMother::random();
			
			$this->id = new Uuid($this->author->id());
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
		}
	}