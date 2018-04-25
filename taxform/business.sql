CREATE TABLE business (
    gid integer NOT NULL,
    x numeric,
    y numeric,
    bld_id integer,
    ppty_use character varying(15),
    ppty_id character varying(15),
    businame character varying,
    busitype character varying,
    busiprod character varying,
    ppty_plt_n character varying(6),
    ppty_strn character varying,
    ppty_addy character varying,
    busireg_st character varying,
    busi_regno numeric,
    busi_inc_y timestamp,
    staf_str integer,
    contact character varying,
    tax_appli character varying,
    tin_status character varying,
    tin_no numeric,
    lt_pd_year timestamp,
    lt_amt_pd numeric
);

CREATE SEQUENCE business_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE business_gid_seq OWNED BY business.gid;

ALTER TABLE ONLY business ALTER COLUMN gid SET DEFAULT nextval('business_gid_seq'::regclass);
