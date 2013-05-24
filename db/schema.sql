--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.4
-- Dumped by pg_dump version 9.1.4
-- Started on 2013-05-24 10:32:26

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_with_oids = false;

--
-- TOC entry 169 (class 1259 OID 17228)
-- Dependencies: 5
-- Name: asset; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE asset (
    gid smallint NOT NULL,
    code character varying(5),
    label character varying(32)
);


--
-- TOC entry 168 (class 1259 OID 17226)
-- Dependencies: 5 169
-- Name: asset_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE asset_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2536 (class 0 OID 0)
-- Dependencies: 168
-- Name: asset_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE asset_gid_seq OWNED BY asset.gid;


--
-- TOC entry 167 (class 1259 OID 17217)
-- Dependencies: 5 1031
-- Name: cell; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE cell (
    id smallint NOT NULL,
    the_geom geometry(Polygon,4326)
);


--
-- TOC entry 166 (class 1259 OID 17215)
-- Dependencies: 167 5
-- Name: cell_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE cell_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2537 (class 0 OID 0)
-- Dependencies: 166
-- Name: cell_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE cell_id_seq OWNED BY cell.id;


--
-- TOC entry 179 (class 1259 OID 25531)
-- Dependencies: 5
-- Name: current_occurence; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE current_occurence (
    asset_code character varying(5),
    cell_id smallint,
    status smallint
);


--
-- TOC entry 171 (class 1259 OID 17238)
-- Dependencies: 5
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
-- TOC entry 178 (class 1259 OID 17333)
-- Dependencies: 5
-- Name: observation_coverage; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE observation_coverage (
    gid smallint NOT NULL,
    observation_id smallint,
    cell_id smallint
);


--
-- TOC entry 177 (class 1259 OID 17331)
-- Dependencies: 5 178
-- Name: observation_coverage_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE observation_coverage_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2538 (class 0 OID 0)
-- Dependencies: 177
-- Name: observation_coverage_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE observation_coverage_gid_seq OWNED BY observation_coverage.gid;


--
-- TOC entry 170 (class 1259 OID 17236)
-- Dependencies: 5 171
-- Name: observation_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE observation_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2539 (class 0 OID 0)
-- Dependencies: 170
-- Name: observation_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE observation_gid_seq OWNED BY observation.gid;


--
-- TOC entry 176 (class 1259 OID 17265)
-- Dependencies: 5
-- Name: r_role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_role (
    gid smallint NOT NULL,
    label character varying(32)
);


--
-- TOC entry 173 (class 1259 OID 17249)
-- Dependencies: 5
-- Name: r_status; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE r_status (
    gid smallint NOT NULL,
    label character varying(16)
);


--
-- TOC entry 172 (class 1259 OID 17247)
-- Dependencies: 5 173
-- Name: r_status_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE r_status_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2540 (class 0 OID 0)
-- Dependencies: 172
-- Name: r_status_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE r_status_gid_seq OWNED BY r_status.gid;


--
-- TOC entry 175 (class 1259 OID 17257)
-- Dependencies: 5
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE "user" (
    gid smallint NOT NULL,
    name character varying(32),
    role_id integer
);


--
-- TOC entry 174 (class 1259 OID 17255)
-- Dependencies: 5 175
-- Name: user_gid_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE user_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2541 (class 0 OID 0)
-- Dependencies: 174
-- Name: user_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE user_gid_seq OWNED BY "user".gid;


--
-- TOC entry 2509 (class 2604 OID 25438)
-- Dependencies: 169 168 169
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset ALTER COLUMN gid SET DEFAULT nextval('asset_gid_seq'::regclass);


--
-- TOC entry 2508 (class 2604 OID 25422)
-- Dependencies: 166 167 167
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY cell ALTER COLUMN id SET DEFAULT nextval('cell_id_seq'::regclass);


--
-- TOC entry 2510 (class 2604 OID 25446)
-- Dependencies: 170 171 171
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation ALTER COLUMN gid SET DEFAULT nextval('observation_gid_seq'::regclass);


--
-- TOC entry 2513 (class 2604 OID 25477)
-- Dependencies: 178 177 178
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage ALTER COLUMN gid SET DEFAULT nextval('observation_coverage_gid_seq'::regclass);


--
-- TOC entry 2511 (class 2604 OID 25499)
-- Dependencies: 173 172 173
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status ALTER COLUMN gid SET DEFAULT nextval('r_status_gid_seq'::regclass);


--
-- TOC entry 2512 (class 2604 OID 25506)
-- Dependencies: 174 175 175
-- Name: gid; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user" ALTER COLUMN gid SET DEFAULT nextval('user_gid_seq'::regclass);


--
-- TOC entry 2518 (class 2606 OID 17235)
-- Dependencies: 169 169
-- Name: asset_code_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset
    ADD CONSTRAINT asset_code_uk UNIQUE (code);


--
-- TOC entry 2520 (class 2606 OID 25440)
-- Dependencies: 169 169
-- Name: asset_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY asset
    ADD CONSTRAINT asset_pk PRIMARY KEY (gid);


--
-- TOC entry 2516 (class 2606 OID 25424)
-- Dependencies: 167 167
-- Name: cell_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY cell
    ADD CONSTRAINT cell_pk PRIMARY KEY (id);


--
-- TOC entry 2532 (class 2606 OID 25479)
-- Dependencies: 178 178
-- Name: observation_coverage_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation_coverage
    ADD CONSTRAINT observation_coverage_pk PRIMARY KEY (gid);


--
-- TOC entry 2522 (class 2606 OID 25448)
-- Dependencies: 171 171
-- Name: observation_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY observation
    ADD CONSTRAINT observation_pk PRIMARY KEY (gid);


--
-- TOC entry 2530 (class 2606 OID 25494)
-- Dependencies: 176 176
-- Name: role_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_role
    ADD CONSTRAINT role_pk PRIMARY KEY (gid);


--
-- TOC entry 2524 (class 2606 OID 25501)
-- Dependencies: 173 173
-- Name: status_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY r_status
    ADD CONSTRAINT status_pk PRIMARY KEY (gid);


--
-- TOC entry 2526 (class 2606 OID 17264)
-- Dependencies: 175 175
-- Name: user_name_uk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_name_uk UNIQUE (name);


--
-- TOC entry 2528 (class 2606 OID 25508)
-- Dependencies: 175 175
-- Name: user_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pk PRIMARY KEY (gid);


--
-- TOC entry 2514 (class 1259 OID 17328)
-- Dependencies: 167 1936
-- Name: cell_geom_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cell_geom_idx ON cell USING gist (the_geom);


--
-- TOC entry 2533 (class 1259 OID 25534)
-- Dependencies: 179
-- Name: co_asset_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX co_asset_idx ON current_occurence USING btree (asset_code);

ALTER TABLE current_occurence CLUSTER ON co_asset_idx;


-- Completed on 2013-05-24 10:32:27

--
-- PostgreSQL database dump complete
--

