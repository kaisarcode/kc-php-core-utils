<?php
class Timespan {
    /**
     * Returns how many full $seconds intervals have passed since the Unix epoch.
     *
     * @param int|float $seconds Interval size in seconds.
     * @return int Number of complete intervals since epoch.
     * @throws InvalidArgumentException If $seconds is not valid.
     */
    public static function get($seconds = 1) {
        if (!is_numeric($seconds) || $seconds == 0) {
            throw new InvalidArgumentException('The value must be numeric and non-zero.');
        }

        return (int) round(time() / $seconds);
    }
}
