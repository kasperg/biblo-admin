--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.1
-- Dumped by pg_dump version 9.5.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: comment; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE comment (
    content character varying(1024) NOT NULL,
    timecreated timestamp with time zone NOT NULL,
    id integer NOT NULL,
    commentownerid integer,
    postid integer
);


ALTER TABLE comment OWNER TO db;

--
-- Name: comment_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE comment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE comment_id_seq OWNER TO db;

--
-- Name: comment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE comment_id_seq OWNED BY comment.id;


--
-- Name: communityrole; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE communityrole (
    name character varying(1024) NOT NULL,
    id integer NOT NULL
);


ALTER TABLE communityrole OWNER TO db;

--
-- Name: communityrole_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE communityrole_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE communityrole_id_seq OWNER TO db;

--
-- Name: communityrole_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE communityrole_id_seq OWNED BY communityrole.id;


--
-- Name: file; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE file (
    container character varying(1024),
    name character varying(1024),
    type character varying(1024),
    url character varying(1024) NOT NULL,
    id integer NOT NULL,
    originalvideoid integer,
    videocollectionid integer,
    resolutionimagefileid integer
);


ALTER TABLE file OWNER TO db;

--
-- Name: file_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE file_id_seq OWNER TO db;

--
-- Name: file_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE file_id_seq OWNED BY file.id;


--
-- Name: flag; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE flag (
    timeflagged timestamp with time zone NOT NULL,
    description character varying(1024) NOT NULL,
    markedasread boolean NOT NULL,
    id integer NOT NULL,
    ownerid integer
);


ALTER TABLE flag OWNER TO db;

--
-- Name: flag_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE flag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE flag_id_seq OWNER TO db;

--
-- Name: flag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE flag_id_seq OWNED BY flag.id;


--
-- Name: flagcomment; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE flagcomment (
    id integer NOT NULL,
    flagid integer,
    commentid integer
);


ALTER TABLE flagcomment OWNER TO db;

--
-- Name: flagcomment_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE flagcomment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE flagcomment_id_seq OWNER TO db;

--
-- Name: flagcomment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE flagcomment_id_seq OWNED BY flagcomment.id;


--
-- Name: flaggroup; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE flaggroup (
    id integer NOT NULL,
    flagid integer,
    groupid integer
);


ALTER TABLE flaggroup OWNER TO db;

--
-- Name: flaggroup_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE flaggroup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE flaggroup_id_seq OWNER TO db;

--
-- Name: flaggroup_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE flaggroup_id_seq OWNED BY flaggroup.id;


--
-- Name: flagpost; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE flagpost (
    id integer NOT NULL,
    flagid integer,
    postid integer
);


ALTER TABLE flagpost OWNER TO db;

--
-- Name: flagpost_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE flagpost_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE flagpost_id_seq OWNER TO db;

--
-- Name: flagpost_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE flagpost_id_seq OWNED BY flagpost.id;


--
-- Name: group; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE "group" (
    name character varying(1024) NOT NULL,
    description character varying(1024) NOT NULL,
    colour character varying(1024),
    timecreated timestamp with time zone NOT NULL,
    id integer NOT NULL,
    groupownerid integer
);


ALTER TABLE "group" OWNER TO db;

--
-- Name: group_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE group_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE group_id_seq OWNER TO db;

--
-- Name: group_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE group_id_seq OWNED BY "group".id;


--
-- Name: groupprofile; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE groupprofile (
    id integer NOT NULL,
    groupid integer,
    profileid integer
);


ALTER TABLE groupprofile OWNER TO db;

--
-- Name: groupprofile_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE groupprofile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE groupprofile_id_seq OWNER TO db;

--
-- Name: groupprofile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE groupprofile_id_seq OWNED BY groupprofile.id;


--
-- Name: imagecollection; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE imagecollection (
    id integer NOT NULL,
    profileimagecollection integer,
    groupcoverimagecollectionid integer,
    postimagecollection integer,
    commentimagecollection integer
);


ALTER TABLE imagecollection OWNER TO db;

--
-- Name: imagecollection_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE imagecollection_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE imagecollection_id_seq OWNER TO db;

--
-- Name: imagecollection_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE imagecollection_id_seq OWNED BY imagecollection.id;


--
-- Name: like; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE "like" (
    item_id character varying(1024) NOT NULL,
    value character varying(1024) NOT NULL,
    id integer NOT NULL,
    profileid integer
);


ALTER TABLE "like" OWNER TO db;

--
-- Name: like_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE like_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE like_id_seq OWNER TO db;

--
-- Name: like_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE like_id_seq OWNED BY "like".id;


--
-- Name: post; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE post (
    title character varying(1024) NOT NULL,
    content character varying(1024) NOT NULL,
    timecreated timestamp with time zone NOT NULL,
    id integer NOT NULL,
    postownerid integer,
    postid integer,
    groupid integer
);


ALTER TABLE post OWNER TO db;

--
-- Name: post_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE post_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE post_id_seq OWNER TO db;

--
-- Name: post_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE post_id_seq OWNED BY post.id;


--
-- Name: profile; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE profile (
    username character varying(1024) NOT NULL,
    displayname character varying(1024),
    favoritelibrary character varying(1024),
    description character varying(1024),
    email character varying(1024),
    phone character varying(1024),
    created timestamp with time zone,
    lastupdated timestamp with time zone,
    hasfilledinprofile boolean,
    birthday timestamp with time zone,
    fullname character varying(1024),
    id integer NOT NULL
);


ALTER TABLE profile OWNER TO db;

--
-- Name: profile_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE profile_id_seq OWNER TO db;

--
-- Name: profile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE profile_id_seq OWNED BY profile.id;


--
-- Name: profilecommunityrole; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE profilecommunityrole (
    id integer NOT NULL,
    profileid integer,
    communityroleid integer
);


ALTER TABLE profilecommunityrole OWNER TO db;

--
-- Name: profilecommunityrole_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE profilecommunityrole_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE profilecommunityrole_id_seq OWNER TO db;

--
-- Name: profilecommunityrole_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE profilecommunityrole_id_seq OWNED BY profilecommunityrole.id;


--
-- Name: quarantine; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE quarantine (
    reason character varying(1024),
    "end" timestamp with time zone NOT NULL,
    start timestamp with time zone NOT NULL,
    id integer NOT NULL,
    quarantinedid integer,
    creatorid integer
);


ALTER TABLE quarantine OWNER TO db;

--
-- Name: quarantine_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE quarantine_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE quarantine_id_seq OWNER TO db;

--
-- Name: quarantine_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE quarantine_id_seq OWNED BY quarantine.id;


--
-- Name: resolution; Type: TABLE; Schema: public; Owner: db
--

CREATE TABLE resolution (
    size character varying(1024) NOT NULL,
    id integer NOT NULL,
    imagecollectionresolutionid integer
);


ALTER TABLE resolution OWNER TO db;

--
-- Name: resolution_id_seq; Type: SEQUENCE; Schema: public; Owner: db
--

CREATE SEQUENCE resolution_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE resolution_id_seq OWNER TO db;

--
-- Name: resolution_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: db
--

ALTER SEQUENCE resolution_id_seq OWNED BY resolution.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY comment ALTER COLUMN id SET DEFAULT nextval('comment_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY communityrole ALTER COLUMN id SET DEFAULT nextval('communityrole_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY file ALTER COLUMN id SET DEFAULT nextval('file_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY flag ALTER COLUMN id SET DEFAULT nextval('flag_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY flagcomment ALTER COLUMN id SET DEFAULT nextval('flagcomment_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY flaggroup ALTER COLUMN id SET DEFAULT nextval('flaggroup_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY flagpost ALTER COLUMN id SET DEFAULT nextval('flagpost_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY "group" ALTER COLUMN id SET DEFAULT nextval('group_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY groupprofile ALTER COLUMN id SET DEFAULT nextval('groupprofile_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY imagecollection ALTER COLUMN id SET DEFAULT nextval('imagecollection_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY "like" ALTER COLUMN id SET DEFAULT nextval('like_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY post ALTER COLUMN id SET DEFAULT nextval('post_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY profile ALTER COLUMN id SET DEFAULT nextval('profile_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY profilecommunityrole ALTER COLUMN id SET DEFAULT nextval('profilecommunityrole_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY quarantine ALTER COLUMN id SET DEFAULT nextval('quarantine_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: db
--

ALTER TABLE ONLY resolution ALTER COLUMN id SET DEFAULT nextval('resolution_id_seq'::regclass);


--
-- Data for Name: comment; Type: TABLE DATA; Schema: public; Owner: db
--

COPY comment (content, timecreated, id, commentownerid, postid) FROM stdin;
André, du er bare den bedste!	2016-03-02 00:00:00+00	1	2	1
\.


--
-- Name: comment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('comment_id_seq', 1, true);


--
-- Data for Name: communityrole; Type: TABLE DATA; Schema: public; Owner: db
--

COPY communityrole (name, id) FROM stdin;
Foo	1
Bar	2
\.


--
-- Name: communityrole_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('communityrole_id_seq', 2, true);


--
-- Data for Name: file; Type: TABLE DATA; Schema: public; Owner: db
--

COPY file (container, name, type, url, id, originalvideoid, videocollectionid, resolutionimagefileid) FROM stdin;
\.


--
-- Name: file_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('file_id_seq', 1, false);


--
-- Data for Name: flag; Type: TABLE DATA; Schema: public; Owner: db
--

COPY flag (timeflagged, description, markedasread, id, ownerid) FROM stdin;
2016-03-02 00:00:00+00	Kasper flirter!	t	1	1
\.


--
-- Name: flag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('flag_id_seq', 1, true);


--
-- Data for Name: flagcomment; Type: TABLE DATA; Schema: public; Owner: db
--

COPY flagcomment (id, flagid, commentid) FROM stdin;
1	1	1
\.


--
-- Name: flagcomment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('flagcomment_id_seq', 1, true);


--
-- Data for Name: flaggroup; Type: TABLE DATA; Schema: public; Owner: db
--

COPY flaggroup (id, flagid, groupid) FROM stdin;
\.


--
-- Name: flaggroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('flaggroup_id_seq', 1, false);


--
-- Data for Name: flagpost; Type: TABLE DATA; Schema: public; Owner: db
--

COPY flagpost (id, flagid, postid) FROM stdin;
\.


--
-- Name: flagpost_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('flagpost_id_seq', 1, false);


--
-- Data for Name: group; Type: TABLE DATA; Schema: public; Owner: db
--

COPY "group" (name, description, colour, timecreated, id, groupownerid) FROM stdin;
Reload	En gruppe for Reloadere	blue	2016-03-02 00:00:00+00	1	2
Kasper & Co.	En gruppe for Kasper og hans kommende venner	red	2016-03-02 00:00:00+00	2	2
\.


--
-- Name: group_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('group_id_seq', 2, true);


--
-- Data for Name: groupprofile; Type: TABLE DATA; Schema: public; Owner: db
--

COPY groupprofile (id, groupid, profileid) FROM stdin;
1	1	1
2	1	2
3	2	2
\.


--
-- Name: groupprofile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('groupprofile_id_seq', 3, true);


--
-- Data for Name: imagecollection; Type: TABLE DATA; Schema: public; Owner: db
--

COPY imagecollection (id, profileimagecollection, groupcoverimagecollectionid, postimagecollection, commentimagecollection) FROM stdin;
\.


--
-- Name: imagecollection_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('imagecollection_id_seq', 1, false);


--
-- Data for Name: like; Type: TABLE DATA; Schema: public; Owner: db
--

COPY "like" (item_id, value, id, profileid) FROM stdin;
\.


--
-- Name: like_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('like_id_seq', 1, false);


--
-- Data for Name: post; Type: TABLE DATA; Schema: public; Owner: db
--

COPY post (title, content, timecreated, id, postownerid, postid, groupid) FROM stdin;
Kasper har lavet en gruppe	Kasper har lavet en gruppe som alle os hos Reload kan bruge til kommunikation!	2016-03-02 00:00:00+00	1	1	\N	1
Hej venner!	Jeg tænkte at det kunne være sjovt for os at have en gruppe sammen.	2016-03-02 00:00:00+00	2	2	\N	2
\.


--
-- Name: post_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('post_id_seq', 2, true);


--
-- Data for Name: profile; Type: TABLE DATA; Schema: public; Owner: db
--

COPY profile (username, displayname, favoritelibrary, description, email, phone, created, lastupdated, hasfilledinprofile, birthday, fullname, id) FROM stdin;
kasper	Master of Disaster	\N	CTO - Reload	kasper@reload.dk	71990555	2016-03-02 00:00:00+00	2016-03-02 00:00:00+00	t	1990-01-08 00:00:00+00	Kasper Garnæs	2
andre	Funk-master 5000	\N	Developer - Reload	andre@reload.dk	30366916	2016-03-02 00:00:00+00	2016-03-02 00:00:00+00	t	1990-01-08 00:00:00+00	André Kallehauge	1
\.


--
-- Name: profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('profile_id_seq', 2, true);


--
-- Data for Name: profilecommunityrole; Type: TABLE DATA; Schema: public; Owner: db
--

COPY profilecommunityrole (id, profileid, communityroleid) FROM stdin;
1	1	\N
2	2	\N
3	1	1
4	2	1
5	2	2
\.


--
-- Name: profilecommunityrole_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('profilecommunityrole_id_seq', 5, true);


--
-- Data for Name: quarantine; Type: TABLE DATA; Schema: public; Owner: db
--

COPY quarantine (reason, "end", start, id, quarantinedid, creatorid) FROM stdin;
Kasper var ret flirtende. Det giver ét års ban!	2017-03-02 00:00:00+00	2016-03-02 00:00:00+00	1	2	1
\.


--
-- Name: quarantine_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('quarantine_id_seq', 1, true);


--
-- Data for Name: resolution; Type: TABLE DATA; Schema: public; Owner: db
--

COPY resolution (size, id, imagecollectionresolutionid) FROM stdin;
\.


--
-- Name: resolution_id_seq; Type: SEQUENCE SET; Schema: public; Owner: db
--

SELECT pg_catalog.setval('resolution_id_seq', 1, false);


--
-- Name: comment_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY comment
    ADD CONSTRAINT comment_pkey PRIMARY KEY (id);


--
-- Name: communityrole_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY communityrole
    ADD CONSTRAINT communityrole_pkey PRIMARY KEY (id);


--
-- Name: file_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY file
    ADD CONSTRAINT file_pkey PRIMARY KEY (id);


--
-- Name: flag_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY flag
    ADD CONSTRAINT flag_pkey PRIMARY KEY (id);


--
-- Name: flagcomment_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY flagcomment
    ADD CONSTRAINT flagcomment_pkey PRIMARY KEY (id);


--
-- Name: flaggroup_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY flaggroup
    ADD CONSTRAINT flaggroup_pkey PRIMARY KEY (id);


--
-- Name: flagpost_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY flagpost
    ADD CONSTRAINT flagpost_pkey PRIMARY KEY (id);


--
-- Name: group_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY "group"
    ADD CONSTRAINT group_pkey PRIMARY KEY (id);


--
-- Name: groupprofile_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY groupprofile
    ADD CONSTRAINT groupprofile_pkey PRIMARY KEY (id);


--
-- Name: imagecollection_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY imagecollection
    ADD CONSTRAINT imagecollection_pkey PRIMARY KEY (id);


--
-- Name: like_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY "like"
    ADD CONSTRAINT like_pkey PRIMARY KEY (id);


--
-- Name: post_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY post
    ADD CONSTRAINT post_pkey PRIMARY KEY (id);


--
-- Name: profile_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY profile
    ADD CONSTRAINT profile_pkey PRIMARY KEY (id);


--
-- Name: profilecommunityrole_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY profilecommunityrole
    ADD CONSTRAINT profilecommunityrole_pkey PRIMARY KEY (id);


--
-- Name: quarantine_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY quarantine
    ADD CONSTRAINT quarantine_pkey PRIMARY KEY (id);


--
-- Name: resolution_pkey; Type: CONSTRAINT; Schema: public; Owner: db
--

ALTER TABLE ONLY resolution
    ADD CONSTRAINT resolution_pkey PRIMARY KEY (id);


--
-- Name: profile_displayname_idx; Type: INDEX; Schema: public; Owner: db
--

CREATE UNIQUE INDEX profile_displayname_idx ON profile USING btree (displayname);


--
-- Name: profile_username_idx; Type: INDEX; Schema: public; Owner: db
--

CREATE UNIQUE INDEX profile_username_idx ON profile USING btree (username);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

