# get all users who used type
SELECT users.name AS firstName, users.surname AS LastName
FROM daneDni
         INNER JOIN users ON users.id = daneDni.user
         INNER JOIN maxVal ON users.grupaZawodowa = maxVal.userGroup AND daneDni.typeDay = maxVal.type
WHERE date = '2021-12-02'
  AND daneDni.typeDay = 1
  AND users.grupaZawodowa = 3;

# get max value for type
SELECT IFNULL((SELECT val
               FROM maxVal
               WHERE userGroup = 3
                 AND type = 1), -1) val;

# get all used who used group
SELECT users.name AS firstName, users.surname AS LastName
FROM daneDni
         INNER JOIN typyDni ON typyDni.id = daneDni.typeDay
         INNER JOIN users ON users.id = daneDni.user
         INNER JOIN maxValsGroups
                    ON maxValsGroups.id = typyDni.maxValGroup AND users.grupaZawodowa = maxValsGroups.userGroup
WHERE date = '2021-12-01'
  AND users.grupaZawodowa = 3
  AND (SELECT maxValGroup
       FROM typyDni
                INNER JOIN maxValsGroups ON typyDni.maxValGroup = maxValsGroups.id AND maxValsGroups.userGroup = 3
       WHERE typyDni.id = 1) = maxValsGroups.id;

# get max group value
SELECT IFNULL((SELECT maxValsGroups.val
FROM typyDni
         INNER JOIN maxValsGroups ON typyDni.maxValGroup = maxValsGroups.id
WHERE typyDni.id = 1
  AND maxValsGroups.userGroup = 3),-1) val;