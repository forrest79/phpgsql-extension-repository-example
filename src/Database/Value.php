<?php declare(strict_types=1);

namespace Forrest79\Database;

final class Value
{
	private mixed $value;


	public function __construct(mixed $value)
	{
		$this->value = $value;
	}


	public function get(): mixed
	{
		return $this->value;
	}


	public function getInt(): int
	{
		assert(is_int($this->value));
		return $this->value;
	}


	public function getIntNullable(): int|null
	{
		assert($this->value === null || is_int($this->value));
		return $this->value;
	}


	public function getFloat(): float
	{
		assert(is_float($this->value));
		return $this->value;
	}


	public function getFloatNullable(): float|null
	{
		assert($this->value === null || is_float($this->value));
		return $this->value;
	}


	public function getBool(): bool
	{
		assert(is_bool($this->value));
		return $this->value;
	}


	public function getBoolNullable(): bool|null
	{
		assert($this->value === null || is_bool($this->value));
		return $this->value;
	}


	public function getString(): string
	{
		assert(is_string($this->value));
		return $this->value;
	}


	public function getStringNullable(): string|null
	{
		assert($this->value === null || is_string($this->value));
		return $this->value;
	}


	/**
	 * @return array<int|string, mixed>
	 */
	public function getArray(): array
	{
		assert(is_array($this->value));
		/** @phpstan-var array<int|string, mixed> */
		return $this->value;
	}


	/**
	 * @return array<int|string, mixed>|null
	 */
	public function getArrayNullable(): array|null
	{
		assert($this->value === null || is_array($this->value));
		/** @phpstan-var array<int|string, mixed>|null */
		return $this->value;
	}


	/**
	 * @return list<mixed>
	 */
	public function getList(): array
	{
		assert(is_array($this->value) && array_is_list($this->value));
		return $this->value;
	}


	/**
	 * @return list<mixed>|null
	 */
	public function getListNullable(): array|null
	{
		assert($this->value === null || (is_array($this->value) && array_is_list($this->value)));
		return $this->value;
	}


	public function getDateTime(): \DateTimeImmutable
	{
		assert($this->value instanceof \DateTimeImmutable);
		return $this->value;
	}


	public function getDateTimeNullable(): \DateTimeImmutable|null
	{
		assert($this->value === null || $this->value instanceof \DateTimeImmutable);
		return $this->value;
	}

}
