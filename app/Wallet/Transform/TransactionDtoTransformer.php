<?php

declare(strict_types=1);

namespace App\Wallet\Transform;

use Bavix\Wallet\Internal\Dto\TransactionDtoInterface;
use Bavix\Wallet\Internal\Transform\TransactionDtoTransformer as BaseTransactionDtoTransformer;
use Bavix\Wallet\Internal\Transform\TransactionDtoTransformerInterface;

final class TransactionDtoTransformer implements TransactionDtoTransformerInterface
{
    public function __construct(
        private BaseTransactionDtoTransformer $transactionDtoTransformer
    ) {
    }

    public function extract(TransactionDtoInterface $dto): array
    {
        $user_type = null;
        $user_id = null;
        $meta = $dto->getMeta();
        if ($dto->getMeta() !== null) {
            $productId = $dto->getMeta()['product_id'] ?? null;
            $user_type = $dto->getMeta()['user_type'] ?? null;
            $user_id = $dto->getMeta()['user_id'] ?? null;
            unset($meta['user_type']);
            unset($meta['user_id']);
        }

        return array_merge($this->transactionDtoTransformer->extract($dto), [
            'product_id' => $productId,
            'user_type' => $user_type,
            'user_id' => $user_id,
            'meta' => $meta,
        ]);
    }
}
