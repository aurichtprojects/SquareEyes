--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_with_oids = false;


CREATE SCHEMA nz AUTHORIZATION postgres;

--
-- Name: asset; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.asset (
    id smallint NOT NULL,
    label character varying(64)
);


--
-- Name: asset_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nz.asset_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nz.asset_gid_seq OWNED BY nz.asset.id;


--
-- Name: baseline_occurence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.baseline_occurence (
    cell_id smallint,
    status_id smallint,
    asset_id smallint,
    source_id smallint,
    year_id smallint
);


--
-- Name: cell; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.cell (
    id smallint NOT NULL,
    the_geom geometry(Polygon,4326)
);


--
-- Name: current_occurence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.current_occurence (
    cell_id smallint,
    status_id smallint,
    asset_id smallint
);


--
-- Name: observation; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.observation (
    id smallint NOT NULL,
    asset_id smallint,
    status_id smallint,
    user_id smallint,
    ts timestamp with time zone DEFAULT now(),
    email_address character varying(64),
    comments text,
    photo character varying(255)
);


--
-- Name: observation_coverage; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.observation_coverage (
    id smallint NOT NULL,
    observation_id smallint,
    cell_id smallint
);


--
-- Name: observation_coverage_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nz.observation_coverage_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: observation_coverage_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nz.observation_coverage_gid_seq OWNED BY nz.observation_coverage.id;


--
-- Name: observation_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nz.observation_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: observation_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nz.observation_gid_seq OWNED BY nz.observation.id;


--
-- Name: r_role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.r_role (
    id smallint NOT NULL,
    label character varying(32)
);


--
-- Name: r_source; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.r_source (
    id smallint NOT NULL,
    label character varying
);


--
-- Name: r_status; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.r_status (
    id smallint NOT NULL,
    label character varying(32)
);


--
-- Name: r_status_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nz.r_status_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: r_status_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nz.r_status_gid_seq OWNED BY nz.r_status.id;


--
-- Name: r_year; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz.r_year (
    id smallint NOT NULL,
    label character varying
);


--
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE nz."user" (
    id smallint NOT NULL,
    name character varying(32),
    role_id integer,
    password character varying(32)
);


--
-- Name: user_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE nz.user_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE nz.user_gid_seq OWNED BY nz."user".id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.asset ALTER COLUMN id SET DEFAULT nextval('nz.asset_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.observation ALTER COLUMN id SET DEFAULT nextval('nz.observation_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.observation_coverage ALTER COLUMN id SET DEFAULT nextval('nz.observation_coverage_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.r_status ALTER COLUMN id SET DEFAULT nextval('nz.r_status_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz."user" ALTER COLUMN id SET DEFAULT nextval('nz.user_gid_seq'::regclass);


--
-- Name: asset_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.asset
    ADD CONSTRAINT asset_pk PRIMARY KEY (id);


--
-- Name: cell_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.cell
    ADD CONSTRAINT cell_pk PRIMARY KEY (id);


--
-- Name: observation_coverage_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.observation_coverage
    ADD CONSTRAINT observation_coverage_pk PRIMARY KEY (id);


--
-- Name: observation_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.observation
    ADD CONSTRAINT observation_pk PRIMARY KEY (id);


--
-- Name: r_source_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.r_source
    ADD CONSTRAINT r_source_pk PRIMARY KEY (id);


--
-- Name: r_year_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.r_year
    ADD CONSTRAINT r_year_pk PRIMARY KEY (id);


--
-- Name: role_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.r_role
    ADD CONSTRAINT role_pk PRIMARY KEY (id);


--
-- Name: status_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz.r_status
    ADD CONSTRAINT status_pk PRIMARY KEY (id);


--
-- Name: user_name_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz."user"
    ADD CONSTRAINT user_name_uk UNIQUE (name);


--
-- Name: user_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY nz."user"
    ADD CONSTRAINT user_pk PRIMARY KEY (id);


--
-- Name: bo_asset_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bo_asset_id_idx ON nz.baseline_occurence USING btree (asset_id);


--
-- Name: cell_the_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cell_the_geom_idx ON nz.cell USING gist (the_geom);


--
-- Name: co_asset_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_asset_id_idx ON nz.current_occurence USING btree (asset_id);


--
-- Name: co_cell_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_cell_id_idx ON nz.current_occurence USING btree (cell_id);


--
-- Function: nz.observation_to_current_occurence()
--

CREATE OR REPLACE FUNCTION nz.observation_to_current_occurence()
  RETURNS trigger AS
$BODY$                                                                                                                                                                                                        
     DECLARE                                                                                                                                                                                                          
         v_cop text := TG_OP;                                                                                                                                                                                         
         v_current_cell_id integer;                                                                                                                                                                                   
         v_current_obs_id integer;                                                                                                                                                                                    
         v_status_id integer;                                                                                                                                                                                         
         v_asset_id integer;                                                                                                                                                                                          
     BEGIN                                                                                                                                                                                                            
         -- Identifying the relevant cell ID based on the trigger                                                                                                                                                     
         IF ((v_cop = 'INSERT') or (v_cop = 'UPDATE')) THEN                                                                                                                                                           
                 v_current_cell_id := NEW.cell_id;                                                                                                                                                                    
                 v_current_obs_id  := NEW.observation_id;                                                                                                                                                             
         ELSE                                                                                                                                                                                                         
                 v_current_cell_id := OLD.cell_id;                                                                                                                                                                    
                 v_current_obs_id  := OLD.observation_id;                                                                                                                                                             
         END IF;                                                                                                                                                                                                      
         -- Getting the baseline value in case there is no previous observation to roll back to                                                                                                                       
         SELECT status_id,asset_id into v_status_id,v_asset_id from nz.observation obs,nz.observation_coverage oc where oc.cell_id = v_current_cell_id and obs.id=oc.observation_id order by obs.ts desc limit 1;     
         -- If not found, because it was the last observation coverage covering this cell                                                                                                                             
         IF NOT FOUND THEN                                                                                                                                                                                            
                 SELECT ba.status_id,ba.asset_id into v_status_id,v_asset_id from nz.baseline_occurence ba,nz.observation obs where obs.id = v_current_obs_id and ba.asset_id=obs.asset_id and ba.cell_id=v_current_cell_id;
                 -- Should we be deleting the observation this coverage was a representant of?                                                                                                                        
                 -- Couldn't find a way to do that without breaking the cleanup                                                                                                                                       
         END IF;                                                                                                                                                                                                      
                                                                                                                                                                                                                      
         -- We should be executing a function that is always able to give the correct current occurence based on baseline and observations                                                                            
         update nz.current_occurence set status_id = v_status_id where cell_id=v_current_cell_id and asset_id = v_asset_id;                                                                                              
                                                                                                                                                                                                                      
         RETURN NULL; -- result is ignored since this is an AFTER trigger                                                                                                                                             
     END;                                                                                                                                                                                                             
 $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION nz.observation_to_current_occurence()
  OWNER TO postgres;

--
-- Name: obs2co; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER obs2co AFTER INSERT OR DELETE OR UPDATE ON nz.observation_coverage FOR EACH ROW EXECUTE PROCEDURE nz.observation_to_current_occurence();


--
-- PostgreSQL database dump complete
--

