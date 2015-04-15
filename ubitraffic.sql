SELECT c.route_short_name, a.stop_id, min(STR_TO_DATE(concat(DATE_FORMAT(now(), "%Y/%m/%d"), " ", a.arrival_time), "%Y/%m/%d %H:%i:%s")-now()) time_till_arrival,
now() + min(STR_TO_DATE(concat(DATE_FORMAT(now(), "%Y/%m/%d"), " ", a.arrival_time), "%Y/%m/%d %H:%i:%s")-now()) arrival_time
FROM `stop_times` a
inner join trips b on a.trip_id = b.trip_id
inner join routes c on b.route_id = c.route_id
where STR_TO_DATE(concat(DATE_FORMAT(now(), "%Y/%m/%d"), " ", a.arrival_time), "%Y/%m/%d %H:%i:%s")-now() > 0
and a.stop_id = 120535
group by c.route_short_name, a.stop_id
-- incoming buses pass a bus stop
select c.route_id, c.route_short_name, b.trip_headsign, a.stop_id, min(a.arrival_time) arrival_time
from stop_times a
inner join trips b on a.trip_id = b.trip_id
inner join routes c on b.route_id = c.route_id
inner join calendar_dates d on b.service_id = d.service_id and d.date = 20150330
where a.stop_id = 120535 and a.arrival_time > '15:30:02'
group by c.route_id, c.route_short_name, b.trip_headsign, a.stop_id
-- ongoing bus
select b.service_id, a.trip_id, b.trip_headsign, b.direction_id, min(arrival_time), max(arrival_time)
from stop_times a
inner join trips b on a.trip_id = b.trip_id
inner join calendar_dates c on b.service_id = c.service_id and c.date = 20150327
where b.route_id = 1 and b.direction_id = 1
group by b.service_id, a.trip_id, b.trip_headsign, b.direction_id
having min(arrival_time)<'15:04:04' and max(arrival_time)>'15:04:04'
ORDER BY min(arrival_time) ASC

-- nearest bus stop
select c.stop_id, c.stop_name, c.stop_lat, c.stop_lon, a.arrival_time
from (	SELECT a.trip_id, min(arrival_time) arrival_time
        FROM `stop_times` a
        where a.trip_id in ('0000199901101051', '0000196901101011', '0000198201101031')
        and a.arrival_time >'15:04:04'
        group by a.trip_id) a
inner join stop_times b on a.trip_id = b.trip_id and a.arrival_time = b.arrival_time
inner join stops c on b.stop_id = c.stop_id

-- total running bus
select a.trip_id, b.route_id, b.direction_id, min(a.arrival_time), max(a.arrival_time)
from stop_times a
inner join trips b on a.trip_id = b.trip_id
inner join calendar_dates c on b.service_id = c.service_id and c.date = 20150327
where b.route_id = 1
group by a.trip_id, b.route_id, b.direction_id
having min(a.arrival_time) <= '15:04:07' and max(a.arrival_time) >= '15:04:07'

-- bus route that pass a bus stop
select b.route_id, b.trip_headsign, min(arrival_time) arrival_time
from stop_times a
inner join trips b on a.trip_id = b.trip_id
inner join calendar_dates c on b.service_id = c.service_id and c.date = 20150330
where a.stop_id = 120535 and a.arrival_time > '15:30:02'
group by b.route_id, b.trip_headsign