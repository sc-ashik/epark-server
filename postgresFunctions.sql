DROP FUNCTION sellstats(text,numeric,numeric)

CREATE OR REPLACE FUNCTION sellStats(_areas text = NULL, _minDuration numeric=NULL, _maxDuration numeric=NULL)
RETURNS TABLE(
   transactionDate text,
   amount numeric,
   duration numeric	
)
AS $$
BEGIN
  Return query 
  with P as (Select id from parkings where (_areas is NULL or area_name = any(string_to_array(_areas,'|')))),
  	   G as (select TO_CHAR(unlocked_at :: DATE, 'yyyy-mm-dd') as transactionDate, round(Sum(fee)::numeric,2) as amount, round((extract(epoch from sum(unlocked_at-locked_at))/3600)::numeric,2) as  duration from completed_transactions as C where C.parking_id in (select * from P) group by 1 order by transactionDate )
       select * from G where (_minDuration is NULL or G.duration>= _minDuration) and (_maxDuration is NULL or G.duration<= _maxDuration) ; 
END;
$$ LANGUAGE 'plpgsql'

select * from sellStats(_areas:='Gombak',_minDuration:=100);
select * from sellStats();
select * from parkings;
select * from completed_transactions

Select TO_CHAR(unlocked_at :: DATE, 'yyyy-mm-dd'), round(Sum(fee)::numeric,2), round((extract(epoch from sum(unlocked_at-locked_at))/3600)::numeric,2),sum(unlocked_at-locked_at) from completed_transactions group by 1






//hasura

CREATE OR REPLACE FUNCTION sellStats(_areas text = NULL, _minDuration numeric=NULL, _maxDuration numeric=NULL)
RETURNS SETOF dailytransactions
AS $$
BEGIN
  Return query 
  with P as (Select id from parkings where (_areas is NULL or area_name = any(string_to_array(_areas,'|')))),
  	   G as (select TO_CHAR(unlocked_at :: DATE, 'yyyy-mm-dd')::varchar(255) as transactionDate, round(Sum(fee)::numeric,2)::numeric(8,2) as amount, round((extract(epoch from sum(unlocked_at-locked_at))/3600)::numeric,2)::numeric(8,2) as  duration from completed_transactions as C where C.parking_id in (select * from P) group by 1 order by transactionDate )
       select * from G where (_minDuration is NULL or G.duration>= _minDuration) and (_maxDuration is NULL or G.duration<= _maxDuration) ; 
END;
$$ LANGUAGE 'plpgsql' STABLE