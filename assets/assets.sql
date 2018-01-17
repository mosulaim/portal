--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.9
-- Dumped by pg_dump version 9.3.3
-- Started on 2018-01-17 20:29:59

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 284 (class 1259 OID 44825)
-- Name: assets; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE assets (
    gid integer DEFAULT nextval('assets_gid_seq'::regclass) NOT NULL,
    name character varying(52) NOT NULL,
    description text,
    priority character varying(12),
    x numeric,
    y numeric,
    "fileURL" character varying(255),
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone
);



--
-- TOC entry 249 (class 1259 OID 18463)
-- Name: statevote; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE statevote (
    gid integer NOT NULL,
    state character varying(15),
    "A" integer,
    "AA" integer,
    "ACD" integer,
    "ACNP" integer,
    "AD" integer,
    "ADC" integer,
    "APA" integer,
    "APC" integer,
    "APGA" integer,
    "CPP" integer,
    "DPP" integer,
    "FDP" integer,
    "HDP" integer,
    "ID" integer,
    "KP" integer,
    "LP" integer,
    "MPPP" integer,
    "NCP" integer,
    "NNPP" integer,
    "PDC" integer,
    "PDM" integer,
    "PDP" integer,
    "PPA" integer,
    "PPN" integer,
    "SDP" integer,
    "UDP" integer,
    "UPN" integer,
    "UPP" integer,
    leading_party character varying
);



--
-- TOC entry 250 (class 1259 OID 18469)
-- Name: statevote_gid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE statevote_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;



--
-- TOC entry 3639 (class 0 OID 0)
-- Dependencies: 250
-- Name: statevote_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE statevote_gid_seq OWNED BY statevote.gid;


--
-- TOC entry 3512 (class 2604 OID 18471)
-- Name: gid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY statevote ALTER COLUMN gid SET DEFAULT nextval('statevote_gid_seq'::regclass);


--
-- TOC entry 3634 (class 0 OID 44825)
-- Dependencies: 284
-- Data for Name: assets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY assets (gid, name, description, priority, created_at, updated_at, x, y, "fileURL") FROM stdin;
1	atest	data step	\N	2018-01-17 14:47:28.126+00	\N	3.35	6.63	\N
2	dove	tail	now	2018-01-17 14:50:36.348+00	\N	3.37	6.62	\N
3	skill	fall	\N	2018-01-17 14:51:01.056+00	\N	3.36	6.63	\N
\.


--
-- TOC entry 3632 (class 0 OID 18463)
-- Dependencies: 249
-- Data for Name: statevote; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY statevote (gid, state, "A", "AA", "ACD", "ACNP", "AD", "ADC", "APA", "APC", "APGA", "CPP", "DPP", "FDP", "HDP", "ID", "KP", "LP", "MPPP", "NCP", "NNPP", "PDC", "PDM", "PDP", "PPA", "PPN", "SDP", "UDP", "UPN", "UPP", leading_party) FROM stdin;
2	Adamawa	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
4	Anambra	2341945	36345	\N	\N	\N	\N	\N	1023543	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2012543	\N	\N	\N	\N	\N	\N	\N
5	Bauchi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
6	Bayelsa	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	34432	\N	\N	\N	\N	\N	\N	\N
7	Benue	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
8	Borno	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
9	Cross River	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
10	Delta	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
11	Ebonyi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
12	Edo	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
13	Ekiti	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
14	Enugu	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
15	Federal Capital	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
16	Gombe	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
17	Imo	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
18	Jigawa	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
19	Kaduna	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
20	Kano	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
21	Katsina	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
22	Kebbi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
23	Kogi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
26	Nassarawa	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
27	Niger	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
29	Ondo	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
30	Osun	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
31	Oyo	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
32	Plateau	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
34	Sokoto	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
35	Taraba	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
36	Yobe	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
37	Zamfara	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
25	Lagos	123543	\N	\N	\N	\N	\N	\N	6235845	\N	\N	\N	\N	\N	\N	\N	\N	\N	284123	\N	\N	\N	1200603	\N	\N	\N	\N	\N	\N	\N
24	Kwara	24123	\N	\N	\N	\N	\N	\N	2125435	\N	\N	\N	\N	\N	\N	\N	\N	\N	145765	\N	\N	\N	1632123	\N	\N	\N	\N	\N	\N	\N
28	Ogun	9834	\N	\N	\N	\N	\N	\N	3294653	\N	\N	\N	\N	\N	\N	\N	\N	\N	67934	\N	\N	\N	2435098	\N	\N	\N	\N	\N	\N	\N
33	Rivers	1723432	\N	\N	\N	\N	\N	\N	4239123	\N	\N	\N	\N	\N	\N	\N	\N	\N	3215	\N	\N	\N	2127435	\N	\N	\N	\N	\N	\N	\N
1	Abia	200	2000	\N	\N	\N	\N	\N	200000	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
3	Akwa Ibom	20000	124	\N	\N	\N	\N	\N	34857	3475439	\N	\N	432034	203958	\N	\N	\N	\N	\N	\N	\N	\N	948237	\N	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 3640 (class 0 OID 0)
-- Dependencies: 250
-- Name: statevote_gid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('statevote_gid_seq', 37, true);


--
-- TOC entry 3518 (class 2606 OID 44834)
-- Name: assets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY assets
    ADD CONSTRAINT assets_pkey PRIMARY KEY (gid);


--
-- TOC entry 3516 (class 2606 OID 18473)
-- Name: statevote_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY statevote
    ADD CONSTRAINT statevote_pkey PRIMARY KEY (gid);


-- Completed on 2018-01-17 20:29:59

--
-- PostgreSQL database dump complete
--

