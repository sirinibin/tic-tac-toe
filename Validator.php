<?php
require_once("Exception/InvalidBoardException.php");
require_once("Exception/InvalidApiRequestException.php");
require_once("Exception/InvalidPlayerUnitException.php");

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Validator
{

    /**
     * @var boolean
     */
    private $validateUnitCount = true;

    /**
     * @return Validator
     */
    public function validateUnitCount()
    {
        $this->validateUnitCount = true;

        return $this;
    }

    /**
     * @return Validator
     */
    public function doNotValidateUnitCount()
    {
        $this->validateUnitCount = false;

        return $this;
    }

    /**
     * @param array $boardState
     * @param string $playerUnit
     */
    public function validate(array $boardState, $playerUnit)
    {
        $this->validatePlayerUnit($playerUnit);

        if (count($boardState) !== 3) {
            throw new InvalidBoardException('You must provide a 3 lines board');
        }

        $totalX = 0;
        $totalO = 0;

        array_walk(
            $boardState,
            function ($line) use (&$totalX, &$totalO) {
                if (count($line) !== 3) {
                    throw new InvalidBoardException('You must provide a 3 positions line');
                }

                foreach ($line as $positionValue) {
                    $this->validatePositionValue($positionValue);

                    if ($positionValue == 'X') {
                        $totalX++;
                    }

                    if ($positionValue == 'O') {
                        $totalO++;
                    }
                }
            }
        );

        if (abs($totalX - $totalO) > 1 && $this->validateUnitCount) {
            throw new InvalidBoardException(
                "Invalid unit count equality $totalX , $totalO " . abs($totalX - $totalO)
            );
        }
    }

    /**
     * @param string $playerUnit
     */
    private function validatePlayerUnit($playerUnit)
    {
        if (!is_string($playerUnit) || !in_array($playerUnit, ['X', 'O'])) {
            throw new InvalidPlayerUnitException('Invalid playerUnit. Allowed O or X');
        }
    }

    /**
     * @param string $positionValue
     */
    private function validatePositionValue($positionValue)
    {
        if (!is_string($positionValue) || !in_array($positionValue, ['X', 'O', ''])) {
            throw new InvalidBoardException('There is some invalid board position value');
        }
    }
}
