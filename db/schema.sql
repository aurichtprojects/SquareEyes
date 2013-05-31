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
    gid smallint NOT NULL,
    code character varying(3),
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

ALTER SEQUENCE asset_gid_seq OWNED BY asset.gid;


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
    asset_code character varying(3),
    cell_id smallint,
    status smallint
);


--
-- Name: observation; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE observation (
    gid smallint NOT NULL,
    asset_id smallint,
    status smallint,
    user_id smallint,
    ts timestamp with time zone,
    email_address character varying(64),
    comments text,
    photo character varying(255)
);


--
-- Name: observation_coverage; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE observation_coverage (
    gid smallint NOT NULL,
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

ALTER SEQUENCE observation_coverage_gid_seq OWNED BY observation_coverage.gid;


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

ALTER SEQUENCE observation_gid_seq OWNED BY observation.gid;


--
-- Name: r_role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_role (
    gid smallint NOT NULL,
    label character varying(32)
);


--
-- Name: r_status; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_status (
    gid smallint NOT NULL,
    label character varying(16)
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

ALTER SEQUENCE r_status_gid_seq OWNED BY r_status.gid;


--
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "user" (
    gid smallint NOT NULL,
    name character varying(32),
    role_id integer
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

ALTER SEQUENCE user_gid_seq OWNED BY "user".gid;


--
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset ALTER COLUMN gid SET DEFAULT nextval('asset_gid_seq'::regclass);


--
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation ALTER COLUMN gid SET DEFAULT nextval('observation_gid_seq'::regclass);


--
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage ALTER COLUMN gid SET DEFAULT nextval('observation_coverage_gid_seq'::regclass);


--
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status ALTER COLUMN gid SET DEFAULT nextval('r_status_gid_seq'::regclass);


--
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user" ALTER COLUMN gid SET DEFAULT nextval('user_gid_seq'::regclass);


--
-- Name: asset_code_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset
    ADD CONSTRAINT asset_code_uk UNIQUE (code);


--
-- Name: asset_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset
    ADD CONSTRAINT asset_pk PRIMARY KEY (gid);


--
-- Name: cell_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cell
    ADD CONSTRAINT cell_pk PRIMARY KEY (id);


--
-- Name: observation_coverage_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage
    ADD CONSTRAINT observation_coverage_pk PRIMARY KEY (gid);


--
-- Name: observation_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation
    ADD CONSTRAINT observation_pk PRIMARY KEY (gid);


--
-- Name: role_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_role
    ADD CONSTRAINT role_pk PRIMARY KEY (gid);


--
-- Name: status_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status
    ADD CONSTRAINT status_pk PRIMARY KEY (gid);


--
-- Name: user_name_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_name_uk UNIQUE (name);


--
-- Name: user_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pk PRIMARY KEY (gid);


--
-- Name: cell_the_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cell_the_geom_idx ON cell USING gist (the_geom);


--
-- Name: co_asset_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_asset_idx ON current_occurence USING btree (asset_code);

ALTER TABLE current_occurence CLUSTER ON co_asset_idx;


--
-- PostgreSQL database dump complete
--

