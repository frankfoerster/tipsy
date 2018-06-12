<?php

namespace App\Model;

use Cake\ORM\Query;

trait UserTipTrait
{
    public function getTipWinCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 > UserTip.result2'
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }

    public function getTipDrawCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 = UserTip.result2'
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }

    public function getTipLoseCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 < UserTip.result2'
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }

    public function getTotalPointsCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    // win
                    $query->newExpr()->add([
                        'UserTip.result1 > UserTip.result2',
                        'Games.result1 > Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    // lose
                    $query->newExpr()->add([
                        'UserTip.result1 < UserTip.result2',
                        'Games.result1 < Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    // draw
                    $query->newExpr()->add([
                        'UserTip.result1 = UserTip.result2',
                        'Games.result1 = Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    //exact
                    $query->newExpr()->add([
                        'UserTip.result1 = Games.result1',
                        'UserTip.result2 = Games.result2'
                    ])
                ],
                [1, 1, 1, 3],
                ['integer', 'integer', 'integer', 'integer']
            );
    }

    public function getExactCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 IS NOT NULL',
                        'UserTip.result2 IS NOT NULL',
                        'Games.result1 IS NOT NULL',
                        'Games.result2 IS NOT NULL',
                        'UserTip.result1 = Games.result1',
                        'UserTip.result2 = Games.result2'
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }

    public function getTendencyCase(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 IS NOT NULL',
                        'UserTip.result2 IS NOT NULL',
                        'Games.result1 IS NOT NULL',
                        'Games.result2 IS NOT NULL',
                        'UserTip.result1 <> Games.result1',
                        'UserTip.result2 <> Games.result2',
                        [
                            'or' => [
                                '(Games.result1 - Games.result2) < 0 && (UserTip.result1 - UserTip.result2) < 0',
                                '(Games.result1 - Games.result2) > 0 && (UserTip.result1 - UserTip.result2) > 0'
                            ]
                        ]
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }

    public function getTotalValuesVotes(Query $query)
    {
        return $query->newExpr()
            ->addCase(
                [
                    $query->newExpr()->add([
                        'UserTip.result1 IS NOT NULL',
                        'UserTip.result2 IS NOT NULL',
                        'Games.result1 IS NOT NULL',
                        'Games.result2 IS NOT NULL'
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );
    }
}
