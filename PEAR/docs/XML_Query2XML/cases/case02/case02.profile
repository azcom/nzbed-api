FROM_DB FROM_CACHE CACHED AVG_DURATION DURATION_SUM SQL
1       0          false  0.0162148475 0.0162148475 SELECT
        *
     FROM
        artist
        LEFT JOIN album ON album.artist_id = artist.artistid
     ORDER BY
        artist.artistid,
        album.albumid

TOTAL_DURATION: 0.031822919845581
DB_DURATION:    0.025322914123535
