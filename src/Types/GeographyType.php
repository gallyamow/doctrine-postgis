<?php

declare(strict_types=1);

namespace Jsor\Doctrine\PostGIS\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class GeographyType extends PostGISType
{
    public function getName(): string
    {
        return PostGISType::GEOGRAPHY;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return json_decode($value, true, 512);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return sprintf('ST_GeomFromGeoJSON(%s)', $sqlExpr);
    }

    public function getNormalizedPostGISColumnOptions(array $options = []): array
    {
        $srid = (int) ($options['srid'] ?? 4326);

        if (0 === $srid) {
            $srid = 4326;
        }

        return [
            'geometry_type' => strtoupper($options['geometry_type'] ?? 'GEOMETRY'),
            'srid' => $srid,
        ];
    }
}
