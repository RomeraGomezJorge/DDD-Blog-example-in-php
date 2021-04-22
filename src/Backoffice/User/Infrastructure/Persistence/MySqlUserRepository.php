<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\Persistence;
	
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\User\Domain\User;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
	
	final class MySqlUserRepository extends DoctrineRepository implements UserRepository
	{
		const NOT_FOUND = null;
		const ENTITY_CLASS = User::class;
		const NOT_SETTING_VALUE = null;
		private ?int $totalMatchingRows = null;
		
		public function save(User $user): void
		{
			$this->persist($user);
		}
		
		public function search(Uuid $id): ?User
		{
			return $this->repository(self::ENTITY_CLASS)->find($id);
		}
		
		public function searchByEmail(string $email): ?User
		{
			return $this->repository(self::ENTITY_CLASS)->findOneBy(['email' => $email]);
		}
		
		public function searchAll(): array
		{
			return $this->repository(self::ENTITY_CLASS)->findAll();
		}
		
		public function isAvailable(array $criteria): bool
		{
			return !((bool)$this->repository(self::ENTITY_CLASS)->findOneBy($criteria));
		}
		
		public function matching(Criteria $criteria, ?Role $rolesFound): array
		{
			$matching = $this->getMatchingFrom($criteria, $rolesFound);
			
			$this->totalMatchingRows = $matching->count();
			
			return $matching->toArray();
		}
		
		private function getMatchingFrom(Criteria $criteria, ?Role $rolesFound): Collection
		{
			$doctrineCriteria = $this->isFoundAddAsCriteria($criteria, $rolesFound);
			
			return $this->repository(self::ENTITY_CLASS)->matching($doctrineCriteria);
		}
		
		private function isFoundAddAsCriteria(Criteria $criteria, ?Role $rolesFound): DoctrineCriteria
		{
			$doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
			
			if ($rolesFound === self::NOT_FOUND) {
				return $doctrineCriteria;
			}
			
			return $doctrineCriteria->andWhere(
				DoctrineCriteria::expr()->eq('role', $rolesFound)
			);
		}
		
		public function totalMatchingRows(Criteria $criteria, ?Role $rolesFound): int
		{
			if ($this->totalMatchingRows === self::NOT_SETTING_VALUE) {
				return $this->getMatchingFrom($criteria, $rolesFound)->count();
			}
			
			return $this->totalMatchingRows;
		}
		
		public function delete(User $user): void
		{
			$this->remove($user);
		}
	}