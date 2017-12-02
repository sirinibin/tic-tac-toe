<?php
/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class WinnerVerifier
{

    /**
     * @var Validator
     */
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return $this
     */
    public function validateUnitCount()
    {
        $this->validator
            ->validateUnitCount();

        return $this;
    }

    /**
     * @return $this
     */
    public function doNotValidateUnitCount()
    {
        $this->validator
            ->doNotValidateUnitCount();

        return $this;
    }

    /**
     * Returns the winner position if it exists.
     * Example: [[2, 0], [1,1], [0,2], 'O'] - Diagonal win by player 'O'
     *
     * @param array $boardState
     * @param $playerUnit
     * @return array
     */
    public function verifyPosition(array $boardState, $playerUnit)
    {
        $this->validator
            ->validate($boardState, $playerUnit);

        $winnerPositions = $this->getWinnerPositions();
        $playersPositions = $this->groupPlayerPositions($boardState);

        foreach ($winnerPositions as $winnerPosition) {
            if ($playerWinnerPosition = $this->handlePlayerPosition($playersPositions, $winnerPosition)) {
                return $playerWinnerPosition;
            }
        }

        return [];
    }

    /**
     * @param array $playersPositions
     * @param array $winnerPosition
     * @return array
     */
    private function handlePlayerPosition(array $playersPositions, array $winnerPosition)
    {
        foreach ($playersPositions as $playerUnit => $positions) {
            $commonPositions = $this->getCommonWinnerPositions($winnerPosition, $positions);

            sort($winnerPosition);
            sort($commonPositions);

            if ($winnerPosition == $commonPositions) {
                return array_merge($winnerPosition, [$playerUnit]);
            }
        }
    }

    /**
     * @param array $winnerPosition
     * @param $playerPositions
     * @return array
     */
    private function getCommonWinnerPositions(array $winnerPosition, $playerPositions)
    {
        $commonPositions = [];

        array_walk(
            $playerPositions,
            function ($option) use ($winnerPosition, &$commonPositions) {
                if (in_array($option, $winnerPosition)) {
                    $commonPositions[] = $option;
                }
            }
        );

        return $commonPositions;
    }

    /**
     * @param array $boardState
     * @return array
     */
    private function groupPlayerPositions(array $boardState)
    {
        $playersPositions = [
            'X' => [],
            'O' => []
        ];

        foreach ($boardState as $yPosition => $line) {
            foreach ($line as $xPosition => $value) {
                if (!empty($value)) {
                    $playersPositions[$value][] = [$xPosition, $yPosition];
                }
            }
        }

        return $playersPositions;
    }

    /**
     * @return array
     */
    private function getWinnerPositions()
    {
        $winnerPositions = [
            [
                [2, 0],
                [1, 1],
                [0, 2]
            ],
            [
                [0, 0],
                [1, 1],
                [2, 2]
            ]
        ];

        for ($row = 0; $row < 3; $row++) {
            $winnerPositions[] = [
                [$row, 0],
                [$row, 1],
                [$row, 2]
            ];

            $winnerPositions[] = [
                [0, $row],
                [1, $row],
                [2, $row]
            ];
        }

        return $winnerPositions;
    }
}
