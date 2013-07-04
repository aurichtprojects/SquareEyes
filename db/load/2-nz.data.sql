-- load the nz_db shapefile into the database under the name nz_db
-- clean up the column names by renaming them all in lowercase

truncate table nz.cell;

insert into nz.cell select distinct cell_id, ST_Transform(the_geom,4326) from nz.nz_db;

truncate table nz.asset;

insert into nz.asset select distinct weed_id,species from nz.nz_db;

update nz.asset set label='Elaeagnus reflexa' where id=52;

truncate table nz.r_status;

insert into nz.r_status select distinct occ_id, occur from nz.nz_db;

truncate table nz.r_source;

insert into nz.r_source select distinct status_id, cell_type from nz.nz_db order by status_id;

truncate table nz.r_year;

insert into nz.r_year select distinct year_id, year from nz.nz_db;

drop INDEX nz.bo_asset_id_idx;

truncate table nz.baseline_occurence;

insert into nz.baseline_occurence select cell_id,occ_id,weed_id,status_id,year_id from nz.nz_db t order by weed_id;

CREATE INDEX bo_asset_id_idx ON nz.baseline_occurence USING btree (asset_id );

drop INDEX nz.co_asset_id_idx;

drop INDEX nz.co_cell_id_idx;

truncate table nz.current_occurence;

insert into nz.current_occurence select cell_id,(case status_id when 1 then 1 else 3 end) as occ_id,weed_id from nz.nz_db t order by weed_id;

CREATE INDEX co_asset_id_idx ON nz.current_occurence USING btree (asset_id );

CREATE INDEX co_cell_id_idx ON nz.current_occurence USING btree (cell_id );

delete from nz.observation_coverage;

delete from nz.observation;

truncate table nz.r_role;
INSERT INTO nz.r_role(id, label) VALUES (1, 'Moderator');
INSERT INTO nz.r_role(id, label) VALUES (2, 'User');

truncate table nz."user";
INSERT INTO nz."user"(id, name, role_id, password) VALUES (1, 'moderator', 1, 'test');
INSERT INTO nz."user"(id, name, role_id, password) VALUES (2, 'user1', 2, 'test');
INSERT INTO nz."user"(id, name, role_id, password) VALUES (3, 'user2', 2, 'test');

