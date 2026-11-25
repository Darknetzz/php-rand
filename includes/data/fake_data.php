<?php
/**
 * Word lists for the Placeholder Data tool (fake_data_value()). Generic, made-up
 * combinations for test fixtures; not meant to match real people or places.
 */
return [
    'first_names' => [
        'Alex', 'Amara', 'Ben', 'Carla', 'Chen', 'Dana', 'Elias', 'Emma', 'Farah', 'Felix',
        'Grace', 'Hana', 'Ivan', 'Ingrid', 'Jonas', 'Julia', 'Kai', 'Lena', 'Liam', 'Maya',
        'Mateo', 'Nina', 'Noah', 'Olivia', 'Omar', 'Priya', 'Quinn', 'Rosa', 'Sam', 'Sofia',
        'Tariq', 'Tess', 'Uma', 'Victor', 'Wei', 'Yara', 'Zoe', 'Leo', 'Mila', 'Aiden',
    ],
    'last_names' => [
        'Andersen', 'Baker', 'Costa', 'Dubois', 'Evans', 'Fischer', 'Garcia', 'Hansen', 'Ito', 'Jensen',
        'Kowalski', 'Larsen', 'Martin', 'Nakamura', 'Novak', 'Olsen', 'Patel', 'Quinn', 'Rossi', 'Silva',
        'Tanaka', 'Underwood', 'Vasquez', 'Weber', 'Xu', 'Young', 'Zhang', 'Berg', 'Moreau', 'Kim',
    ],
    'streets' => [
        'Maple', 'Oak', 'Pine', 'Cedar', 'Elm', 'Birch', 'Willow', 'Hillside', 'Lakeview', 'River',
        'Meadow', 'Orchard', 'Station', 'Market', 'Church', 'Mill', 'Park', 'Harbor', 'Sunset', 'Garden',
    ],
    'street_types' => ['Street', 'Avenue', 'Road', 'Lane', 'Way', 'Drive', 'Court', 'Place'],
    'cities' => [
        'Springfield', 'Riverton', 'Fairview', 'Lakeside', 'Brookfield', 'Greenville', 'Oakridge',
        'Millbrook', 'Westfield', 'Northport', 'Clearwater', 'Ashford', 'Stonebridge', 'Elmwood',
    ],
    // Reserved names: RFC 2606 / RFC 6761 example domains and the .test TLD
    'domains' => ['example.com', 'example.org', 'example.net', 'mail.test', 'company.test'],
    'url_words' => ['blog', 'docs', 'shop', 'news', 'about', 'products', 'account', 'help', 'events', 'team'],
    // Documentation IPv4 ranges (RFC 5737)
    'ipv4_prefixes' => ['192.0.2', '198.51.100', '203.0.113'],
];
