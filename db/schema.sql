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

--
-- Name: asset; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE asset (
    id smallint NOT NULL,
    label character varying(64)
);


--
-- Name: asset_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE asset_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: asset_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE asset_gid_seq OWNED BY asset.id;


--
-- Name: baseline_occurence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE baseline_occurence (
    cell_id smallint,
    status_id smallint,
    asset_id smallint,
    source_id smallint,
    year_id smallint
);


--
-- Name: cell; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE cell (
    id smallint NOT NULL,
    the_geom geometry(Polygon,4326)
);


--
-- Name: current_occurence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE current_occurence (
    cell_id smallint,
    status_id smallint,
    asset_id smallint
);


--
-- Name: observation; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE observation (
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

CREATE TABLE observation_coverage (
    id smallint NOT NULL,
    observation_id smallint,
    cell_id smallint
);


--
-- Name: observation_coverage_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE observation_coverage_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: observation_coverage_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE observation_coverage_gid_seq OWNED BY observation_coverage.id;


--
-- Name: observation_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE observation_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: observation_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE observation_gid_seq OWNED BY observation.id;


--
-- Name: r_role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_role (
    id smallint NOT NULL,
    label character varying(32)
);


--
-- Name: r_source; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_source (
    id smallint NOT NULL,
    label character varying
);


--
-- Name: r_status; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_status (
    id smallint NOT NULL,
    label character varying(32)
);


--
-- Name: r_status_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE r_status_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: r_status_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE r_status_gid_seq OWNED BY r_status.id;


--
-- Name: r_year; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_year (
    id smallint NOT NULL,
    label character varying
);


--
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "user" (
    id smallint NOT NULL,
    name character varying(32),
    role_id integer,
    password character varying(32)
);


--
-- Name: user_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_gid_seq OWNED BY "user".id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset ALTER COLUMN id SET DEFAULT nextval('asset_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation ALTER COLUMN id SET DEFAULT nextval('observation_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage ALTER COLUMN id SET DEFAULT nextval('observation_coverage_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status ALTER COLUMN id SET DEFAULT nextval('r_status_gid_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user" ALTER COLUMN id SET DEFAULT nextval('user_gid_seq'::regclass);


--
-- Name: asset_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset
    ADD CONSTRAINT asset_pk PRIMARY KEY (id);


--
-- Name: cell_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cell
    ADD CONSTRAINT cell_pk PRIMARY KEY (id);


--
-- Name: observation_coverage_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage
    ADD CONSTRAINT observation_coverage_pk PRIMARY KEY (id);


--
-- Name: observation_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation
    ADD CONSTRAINT observation_pk PRIMARY KEY (id);


--
-- Name: r_source_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_source
    ADD CONSTRAINT r_source_pk PRIMARY KEY (id);


--
-- Name: r_year_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_year
    ADD CONSTRAINT r_year_pk PRIMARY KEY (id);


--
-- Name: role_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_role
    ADD CONSTRAINT role_pk PRIMARY KEY (id);


--
-- Name: status_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status
    ADD CONSTRAINT status_pk PRIMARY KEY (id);


--
-- Name: user_name_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_name_uk UNIQUE (name);


--
-- Name: user_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pk PRIMARY KEY (id);


--
-- Name: bo_asset_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bo_asset_id_idx ON baseline_occurence USING btree (asset_id);


--
-- Name: cell_the_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cell_the_geom_idx ON cell USING gist (the_geom);


--
-- Name: co_asset_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_asset_id_idx ON current_occurence USING btree (asset_id);


--
-- Name: co_cell_id_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_cell_id_idx ON current_occurence USING btree (cell_id);


--
-- Name: obs2co; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER obs2co AFTER INSERT OR DELETE OR UPDATE ON observation_coverage FOR EACH ROW EXECUTE PROCEDURE observation_to_current_occurence();


--
-- PostgreSQL database dump complete
--

