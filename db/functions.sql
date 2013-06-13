                                                                                                                                                                                                pg_get_functiondef                                                                                                                                                                                                 
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 CREATE OR REPLACE FUNCTION public.observation_to_current_occurence()                                                                                                                                                                                                                                                                                                                                             +
  RETURNS trigger                                                                                                                                                                                                                                                                                                                                                                                                 +
  LANGUAGE plpgsql                                                                                                                                                                                                                                                                                                                                                                                                +
 AS $function$                                                                                                                                                                                                                                                                                                                                                                                                    +
     BEGIN                                                                                                                                                                                                                                                                                                                                                                                                        +
         --                                                                                                                                                                                                                                                                                                                                                                                                       +
         -- We should be executing a function that is always able to give the correct current occurence based on baseline and observations                                                                                                                                                                                                                                                                        +
         update current_occurence set status_id = (select obs.status_id from observation obs,observation_coverage oc where oc.cell_id = NEW.cell_id and obs.id=oc.observation_id order by obs.ts desc limit 1) where cell_id=NEW.cell_id and asset_id = (select obs.asset_id from observation obs,observation_coverage oc where oc.cell_id=NEW.cell_id and obs.id=oc.observation_id order by obs.ts desc limit 1);+
                                                                                                                                                                                                                                                                                                                                                                                                                  +
         RETURN NULL; -- result is ignored since this is an AFTER trigger                                                                                                                                                                                                                                                                                                                                         +
     END;                                                                                                                                                                                                                                                                                                                                                                                                         +
 $function$                                                                                                                                                                                                                                                                                                                                                                                                       +
 
(1 row)
