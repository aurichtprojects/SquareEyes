drop INDEX bo_asset_id_idx;

truncate table baseline_occurence;

insert into baseline_occurence select cell_id,occ_id,weed_id,status_id,year_id from nz_db t order by weed_id;

CREATE INDEX bo_asset_id_idx ON baseline_occurence USING btree (asset_id );

drop INDEX co_asset_id_idx;

drop INDEX co_cell_id_idx;

truncate table current_occurence;

insert into current_occurence select cell_id,occ_id,weed_id from nz_db t order by weed_id;

CREATE INDEX co_asset_id_idx ON current_occurence USING btree (asset_id );

CREATE INDEX co_cell_id_idx ON current_occurence USING btree (cell_id );

delete from observation_coverage;

delete from observation;