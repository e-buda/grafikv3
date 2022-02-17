SELECT IF(IFNULL((SELECT COUNT(*)
                  FROM daneDni
                           INNER JOIN typyDni on typyDni.id = daneDni.typeDay
                           INNER JOIN maxValsGroups on typyDni.maxValGroup = maxValsGroups.id
                  WHERE maxValsGroups.id =
                        (SELECT maxValsGroups.id
                         FROM maxValsGroups
                                  INNER JOIN typyDni on typyDni.maxValGroup = maxValsGroups.id
                         WHERE typyDni.id = 2
                           AND maxValsGroups.userGroup = 3)
                    AND date = '2022-12-01'
                  GROUP BY maxValsGroups.id), 0) <
          IFNULL((SELECT maxValsGroups.val
                  FROM maxValsGroups
                           INNER JOIN typyDni on typyDni.maxValGroup = maxValsGroups.id
                  WHERE typyDni.id = 2
                    AND maxValsGroups.userGroup = 3), IFNULL((SELECT COUNT(*)
                                                              FROM daneDni
                                                                       INNER JOIN typyDni on typyDni.id = daneDni.typeDay
                                                                       INNER JOIN maxValsGroups on typyDni.maxValGroup = maxValsGroups.id
                                                              WHERE maxValsGroups.id =
                                                                    (SELECT maxValsGroups.id
                                                                     FROM maxValsGroups
                                                                              INNER JOIN typyDni on typyDni.maxValGroup = maxValsGroups.id
                                                                     WHERE typyDni.id = 2
                                                                       AND maxValsGroups.userGroup = 3)
                                                                AND date = '2022-12-01'
                                                              GROUP BY maxValsGroups.id), 0) + 1) AND
          IFNULL((SELECT COUNT(typeDay)
                  FROM daneDni
                           INNER JOIN users on daneDni.user = users.id
                  WHERE typeDay = 2
                    AND date = '2022-12-01'
                    AND users.grupaZawodowa = 3
                  GROUP BY typeDay), 0) <
          IFNULL((SELECT val FROM maxVal WHERE type = 2 AND userGroup = 3), IFNULL((SELECT COUNT(typeDay)
                                                                                    FROM daneDni
                                                                                             INNER JOIN users on daneDni.user = users.id
                                                                                    WHERE typeDay = 2
                                                                                      AND date = '2022-12-01'
                                                                                      AND users.grupaZawodowa = 3
                                                                                    GROUP BY typeDay), 0) + 1)
           , 'true', 'false') as canWork