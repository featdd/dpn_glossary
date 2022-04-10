<?php
declare(strict_types=1);

namespace Featdd\DpnGlossary\Routing\Aspect;

/***
 *
 * This file is part of the "dreipunktnull Glossar" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Daniel Dorndorf <dorndorf@featdd.de>
 *
 ***/

use TYPO3\CMS\Core\Routing\Aspect\StaticMappableAspectInterface;

/**
 * @package Featdd\DpnGlossary\Routing\Aspect
 */
class StaticMultiRangeMapper implements StaticMappableAspectInterface
{
    /**
     * @var array
     */
    protected $ranges;

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        foreach ($settings['ranges'] as $range) {
            $start = $range['start'] ?? null;
            $end = $range['end'] ?? null;

            if (false === \is_string($start)) {
                throw new \InvalidArgumentException('start must be string', 1537277163);
            }
            if (false === \is_string($end)) {
                throw new \InvalidArgumentException('end must be string', 1537277164);
            }

            $this->ranges[] = $this->buildRange($start, $end);
        }
    }

    /**
     * @param string $value
     * @return string|null
     */
    public function generate(string $value): ?string
    {
        return $this->respondWhenInRange($value);
    }

    /**
     * @param string $value
     * @return string|null
     */
    public function resolve(string $value): ?string
    {
        return $this->respondWhenInRange($value);
    }

    /**
     * @param string $value
     * @return null|string
     */
    protected function respondWhenInRange(string $value): ?string
    {
        foreach ($this->ranges as $range) {
            if (true === \in_array($value, $range, true)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     */
    protected function buildRange(string $start, string $end): array
    {
        $range = array_map('\strval', range($start, $end));

        if (1000 < \count($range)) {
            throw new \LengthException(
                'Range is larger than 1000 items',
                1537696771
            );
        }

        return $range;
    }
}
