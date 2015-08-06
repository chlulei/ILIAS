<?php

/* Copyright (c) 1998-2012 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Mime type determination.
 *
 * @author  Alex Killing <alex.killing@gmx.de>
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 */
class ilMimeTypeUtil {

	const VIDEO__YOUTUBE = 'video/youtube';
	const VIDEO__VIMEO = 'video/vimeo';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_SPREADSHEETML_SHEET = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_SPREADSHEETML_TEMPLATE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.template';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_TEMPLATE = 'application/vnd.openxmlformats-officedocument.presentationml.template';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_SLIDESHOW = 'application/vnd.openxmlformats-officedocument.presentationml.slideshow';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_PRESENTATION = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_SLIDE = 'application/vnd.openxmlformats-officedocument.presentationml.slide';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_WORDPROCESSINGML_DOCUMENT = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
	const APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_WORDPROCESSINGML_TEMPLATE = 'application/vnd.openxmlformats-officedocument.wordprocessingml.template';
	const APPLICATION__VND_MS_EXCEL_ADDIN_MACRO_ENABLED_12 = 'application/vnd.ms-excel.addin.macroEnabled.12';
	const APPLICATION__VND_MS_EXCEL_SHEET_BINARY_MACRO_ENABLED_12 = 'application/vnd.ms-excel.sheet.binary.macroEnabled.12';
	const VIDEO__3_GPP = 'video/3gpp';
	const VIDEO__X_MS_ASF = 'video/x-ms-asf';
	const APPLICATION__ASTOUND = 'application/astound';
	const IMAGE__X_MS_BMP = 'image/x-ms-bmp';
	const APPLICATION__X_JAVA_APPLET = 'application/x-java-applet';
	const VIDEO__X_FLV = 'video/x-flv';
	const VIDEO__MP_4 = 'video/mp4';
	const APPLICATION__OGG = 'application/ogg';
	const AUDIO__OGG = 'audio/ogg';
	const VIDEO__OGG = 'video/ogg';
	const VIDEO__X_MS_WM = 'video/x-ms-wm';
	const AUDIO__X_MS_WMA = 'audio/x-ms-wma';
	const VIDEO__X_MS_WMD = 'video/x-ms-wmd';
	const VIDEO__X_MS_WMV = 'video/x-ms-wmv';
	const VIDEO__X_MS_WMX = 'video/x-ms-wmx';
	const VIDEO__X_MS_WMZ = 'video/x-ms-wmz';
	const VIDEO__X_MS_WVX = 'video/x-ms-wvx';
	const X_WORLD__X_3DMF = 'x-world/x-3dmf';
	const APPLICATION__OCTET_STREAM = 'application/octet-stream';
	const APPLICATION__X_AUTHORWARE_BIN = 'application/x-authorware-bin';
	const APPLICATION__X_AUTHORWARE_MAP = 'application/x-authorware-map';
	const APPLICATION__X_AUTHORWARE_SEG = 'application/x-authorware-seg';
	const TEXT__VND_ABC = 'text/vnd.abc';
	const TEXT__HTML = 'text/html';
	const VIDEO__ANIMAFLEX = 'video/animaflex';
	const APPLICATION__POSTSCRIPT = 'application/postscript';
	const AUDIO__AIFF = 'audio/aiff';
	const AUDIO__X_AIFF = 'audio/x-aiff';
	const APPLICATION__X_AIM = 'application/x-aim';
	const TEXT__X_AUDIOSOFT_INTRA = 'text/x-audiosoft-intra';
	const APPLICATION__X_NAVI_ANIMATION = 'application/x-navi-animation';
	const APPLICATION__X_NOKIA_9000_COMMUNICATOR_ADD_ON_SOFTWARE = 'application/x-nokia-9000-communicator-add-on-software';
	const APPLICATION__MIME = 'application/mime';
	const APPLICATION__ARJ = 'application/arj';
	const IMAGE__X_JG = 'image/x-jg';
	const TEXT__X_ASM = 'text/x-asm';
	const TEXT__ASP = 'text/asp';
	const APPLICATION__X_MPLAYER2 = 'application/x-mplayer2';
	const VIDEO__X_MS_ASF_PLUGIN = 'video/x-ms-asf-plugin';
	const AUDIO__BASIC = 'audio/basic';
	const AUDIO__X_AU = 'audio/x-au';
	const APPLICATION__X_TROFF_MSVIDEO = 'application/x-troff-msvideo';
	const VIDEO__AVI = 'video/avi';
	const VIDEO__MSVIDEO = 'video/msvideo';
	const VIDEO__X_MSVIDEO = 'video/x-msvideo';
	const VIDEO__AVS_VIDEO = 'video/avs-video';
	const APPLICATION__X_BCPIO = 'application/x-bcpio';
	const APPLICATION__MAC_BINARY = 'application/mac-binary';
	const APPLICATION__MACBINARY = 'application/macbinary';
	const APPLICATION__X_BINARY = 'application/x-binary';
	const APPLICATION__X_MACBINARY = 'application/x-macbinary';
	const IMAGE__BMP = 'image/bmp';
	const IMAGE__X_WINDOWS_BMP = 'image/x-windows-bmp';
	const APPLICATION__BOOK = 'application/book';
	const APPLICATION__X_BZIP2 = 'application/x-bzip2';
	const APPLICATION__X_BSH = 'application/x-bsh';
	const APPLICATION__X_BZIP = 'application/x-bzip';
	const TEXT__PLAIN = 'text/plain';
	const TEXT__X_C = 'text/x-c';
	const APPLICATION__VND_MS_PKI_SECCAT = 'application/vnd.ms-pki.seccat';
	const APPLICATION__CLARISCAD = 'application/clariscad';
	const APPLICATION__X_COCOA = 'application/x-cocoa';
	const APPLICATION__CDF = 'application/cdf';
	const APPLICATION__X_CDF = 'application/x-cdf';
	const APPLICATION__X_NETCDF = 'application/x-netcdf';
	const APPLICATION__PKIX_CERT = 'application/pkix-cert';
	const APPLICATION__X_X509_CA_CERT = 'application/x-x509-ca-cert';
	const APPLICATION__X_CHAT = 'application/x-chat';
	const APPLICATION__JAVA = 'application/java';
	const APPLICATION__JAVA_BYTE_CODE = 'application/java-byte-code';
	const APPLICATION__X_JAVA_CLASS = 'application/x-java-class';
	const APPLICATION__X_CPIO = 'application/x-cpio';
	const APPLICATION__MAC_COMPACTPRO = 'application/mac-compactpro';
	const APPLICATION__X_COMPACTPRO = 'application/x-compactpro';
	const APPLICATION__X_CPT = 'application/x-cpt';
	const APPLICATION__PKCS_CRL = 'application/pkcs-crl';
	const APPLICATION__PKIX_CRL = 'application/pkix-crl';
	const APPLICATION__X_X509_USER_CERT = 'application/x-x509-user-cert';
	const APPLICATION__X_CSH = 'application/x-csh';
	const TEXT__X_SCRIPT_CSH = 'text/x-script.csh';
	const APPLICATION__X_POINTPLUS = 'application/x-pointplus';
	const TEXT__CSS = 'text/css';
	const APPLICATION__X_DIRECTOR = 'application/x-director';
	const APPLICATION__X_DEEPV = 'application/x-deepv';
	const VIDEO__X_DV = 'video/x-dv';
	const VIDEO__DL = 'video/dl';
	const VIDEO__X_DL = 'video/x-dl';
	const APPLICATION__MSWORD = 'application/msword';
	const APPLICATION__COMMONGROUND = 'application/commonground';
	const APPLICATION__DRAFTING = 'application/drafting';
	const APPLICATION__X_DVI = 'application/x-dvi';
	const MODEL__VND_DWF = 'model/vnd.dwf';
	const APPLICATION__ACAD = 'application/acad';
	const IMAGE__VND_DWG = 'image/vnd.dwg';
	const IMAGE__X_DWG = 'image/x-dwg';
	const APPLICATION__DXF = 'application/dxf';
	const TEXT__X_SCRIPT_ELISP = 'text/x-script.elisp';
	const APPLICATION__X_ELC = 'application/x-elc';
	const APPLICATION__X_ENVOY = 'application/x-envoy';
	const APPLICATION__X_ESREHBER = 'application/x-esrehber';
	const TEXT__X_SETEXT = 'text/x-setext';
	const APPLICATION__ENVOY = 'application/envoy';
	const TEXT__X_FORTRAN = 'text/x-fortran';
	const APPLICATION__VND_FDF = 'application/vnd.fdf';
	const APPLICATION__FRACTALS = 'application/fractals';
	const IMAGE__FIF = 'image/fif';
	const VIDEO__FLI = 'video/fli';
	const VIDEO__X_FLI = 'video/x-fli';
	const IMAGE__FLORIAN = 'image/florian';
	const TEXT__VND_FMI_FLEXSTOR = 'text/vnd.fmi.flexstor';
	const VIDEO__X_ATOMIC3D_FEATURE = 'video/x-atomic3d-feature';
	const IMAGE__VND_FPX = 'image/vnd.fpx';
	const IMAGE__VND_NET_FPX = 'image/vnd.net-fpx';
	const APPLICATION__FREELOADER = 'application/freeloader';
	const AUDIO__MAKE = 'audio/make';
	const IMAGE__G3FAX = 'image/g3fax';
	const IMAGE__GIF = 'image/gif';
	const VIDEO__GL = 'video/gl';
	const VIDEO__X_GL = 'video/x-gl';
	const AUDIO__X_GSM = 'audio/x-gsm';
	const APPLICATION__X_GSP = 'application/x-gsp';
	const APPLICATION__X_GSS = 'application/x-gss';
	const APPLICATION__X_GTAR = 'application/x-gtar';
	const APPLICATION__X_COMPRESSED = 'application/x-compressed';
	const APPLICATION__X_GZIP = 'application/x-gzip';
	const MULTIPART__X_GZIP = 'multipart/x-gzip';
	const TEXT__X_H = 'text/x-h';
	const APPLICATION__X_HDF = 'application/x-hdf';
	const APPLICATION__X_HELPFILE = 'application/x-helpfile';
	const APPLICATION__VND_HP_HPGL = 'application/vnd.hp-hpgl';
	const TEXT__X_SCRIPT = 'text/x-script';
	const APPLICATION__HLP = 'application/hlp';
	const APPLICATION__X_WINHELP = 'application/x-winhelp';
	const APPLICATION__BINHEX = 'application/binhex';
	const APPLICATION__BINHEX4 = 'application/binhex4';
	const APPLICATION__MAC_BINHEX = 'application/mac-binhex';
	const APPLICATION__MAC_BINHEX40 = 'application/mac-binhex40';
	const APPLICATION__X_BINHEX40 = 'application/x-binhex40';
	const APPLICATION__X_MAC_BINHEX40 = 'application/x-mac-binhex40';
	const APPLICATION__HTA = 'application/hta';
	const TEXT__X_COMPONENT = 'text/x-component';
	const TEXT__WEBVIEWHTML = 'text/webviewhtml';
	const X_CONFERENCE__X_COOLTALK = 'x-conference/x-cooltalk';
	const IMAGE__X_ICON = 'image/x-icon';
	const IMAGE__IEF = 'image/ief';
	const APPLICATION__IGES = 'application/iges';
	const MODEL__IGES = 'model/iges';
	const APPLICATION__X_IMA = 'application/x-ima';
	const APPLICATION__X_HTTPD_IMAP = 'application/x-httpd-imap';
	const APPLICATION__INF = 'application/inf';
	const APPLICATION__X_INTERNETT_SIGNUP = 'application/x-internett-signup';
	const APPLICATION__X_IP2 = 'application/x-ip2';
	const VIDEO__X_ISVIDEO = 'video/x-isvideo';
	const AUDIO__IT = 'audio/it';
	const APPLICATION__X_INVENTOR = 'application/x-inventor';
	const I_WORLD__I_VRML = 'i-world/i-vrml';
	const APPLICATION__X_LIVESCREEN = 'application/x-livescreen';
	const AUDIO__X_JAM = 'audio/x-jam';
	const TEXT__X_JAVA_SOURCE = 'text/x-java-source';
	const APPLICATION__X_JAVA_COMMERCE = 'application/x-java-commerce';
	const IMAGE__JPEG = 'image/jpeg';
	const IMAGE__PJPEG = 'image/pjpeg';
	const IMAGE__X_JPS = 'image/x-jps';
	const APPLICATION__X_JAVASCRIPT = 'application/x-javascript';
	const APPLICATION__JAVASCRIPT = 'application/javascript';
	const APPLICATION__ECMASCRIPT = 'application/ecmascript';
	const TEXT__JAVASCRIPT = 'text/javascript';
	const TEXT__ECMASCRIPT = 'text/ecmascript';
	const IMAGE__JUTVISION = 'image/jutvision';
	const AUDIO__MIDI = 'audio/midi';
	const MUSIC__X_KARAOKE = 'music/x-karaoke';
	const APPLICATION__X_KSH = 'application/x-ksh';
	const TEXT__X_SCRIPT_KSH = 'text/x-script.ksh';
	const AUDIO__NSPAUDIO = 'audio/nspaudio';
	const AUDIO__X_NSPAUDIO = 'audio/x-nspaudio';
	const AUDIO__X_LIVEAUDIO = 'audio/x-liveaudio';
	const APPLICATION__X_LATEX = 'application/x-latex';
	const APPLICATION__LHA = 'application/lha';
	const APPLICATION__X_LHA = 'application/x-lha';
	const APPLICATION__X_LISP = 'application/x-lisp';
	const TEXT__X_SCRIPT_LISP = 'text/x-script.lisp';
	const TEXT__X_LA_ASF = 'text/x-la-asf';
	const APPLICATION__X_LZH = 'application/x-lzh';
	const APPLICATION__LZX = 'application/lzx';
	const APPLICATION__X_LZX = 'application/x-lzx';
	const TEXT__X_M = 'text/x-m';
	const VIDEO__MPEG = 'video/mpeg';
	const AUDIO__MPEG = 'audio/mpeg';
	const AUDIO__X_MPEQURL = 'audio/x-mpequrl';
	const APPLICATION__X_TROFF_MAN = 'application/x-troff-man';
	const APPLICATION__X_NAVIMAP = 'application/x-navimap';
	const APPLICATION__MBEDLET = 'application/mbedlet';
	const APPLICATION__X_MAGIC_CAP_PACKAGE_1_0 = 'application/x-magic-cap-package-1.0';
	const APPLICATION__MCAD = 'application/mcad';
	const APPLICATION__X_MATHCAD = 'application/x-mathcad';
	const IMAGE__VASA = 'image/vasa';
	const TEXT__MCF = 'text/mcf';
	const APPLICATION__NETMC = 'application/netmc';
	const APPLICATION__X_TROFF_ME = 'application/x-troff-me';
	const MESSAGE__RFC822 = 'message/rfc822';
	const APPLICATION__X_MIDI = 'application/x-midi';
	const AUDIO__X_MID = 'audio/x-mid';
	const AUDIO__X_MIDI = 'audio/x-midi';
	const MUSIC__CRESCENDO = 'music/crescendo';
	const X_MUSIC__X_MIDI = 'x-music/x-midi';
	const APPLICATION__X_FRAME = 'application/x-frame';
	const APPLICATION__X_MIF = 'application/x-mif';
	const WWW__MIME = 'www/mime';
	const AUDIO__X_VND_AUDIOEXPLOSION_MJUICEMEDIAFILE = 'audio/x-vnd.audioexplosion.mjuicemediafile';
	const VIDEO__X_MOTION_JPEG = 'video/x-motion-jpeg';
	const APPLICATION__BASE64 = 'application/base64';
	const APPLICATION__X_MEME = 'application/x-meme';
	const AUDIO__MOD = 'audio/mod';
	const AUDIO__X_MOD = 'audio/x-mod';
	const VIDEO__QUICKTIME = 'video/quicktime';
	const VIDEO__X_SGI_MOVIE = 'video/x-sgi-movie';
	const AUDIO__X_MPEG = 'audio/x-mpeg';
	const VIDEO__X_MPEG = 'video/x-mpeg';
	const VIDEO__X_MPEQ2A = 'video/x-mpeq2a';
	const AUDIO__MPEG3 = 'audio/mpeg3';
	const AUDIO__X_MPEG_3 = 'audio/x-mpeg-3';
	const APPLICATION__X_PROJECT = 'application/x-project';
	const APPLICATION__VND_MS_PROJECT = 'application/vnd.ms-project';
	const APPLICATION__MARC = 'application/marc';
	const APPLICATION__X_TROFF_MS = 'application/x-troff-ms';
	const APPLICATION__X_VND_AUDIOEXPLOSION_MZZ = 'application/x-vnd.audioexplosion.mzz';
	const IMAGE__NAPLPS = 'image/naplps';
	const APPLICATION__VND_NOKIA_CONFIGURATION_MESSAGE = 'application/vnd.nokia.configuration-message';
	const IMAGE__X_NIFF = 'image/x-niff';
	const APPLICATION__X_MIX_TRANSFER = 'application/x-mix-transfer';
	const APPLICATION__X_CONFERENCE = 'application/x-conference';
	const APPLICATION__X_NAVIDOC = 'application/x-navidoc';
	const APPLICATION__ODA = 'application/oda';
	const APPLICATION__X_OMC = 'application/x-omc';
	const APPLICATION__X_OMCDATAMAKER = 'application/x-omcdatamaker';
	const APPLICATION__X_OMCREGERATOR = 'application/x-omcregerator';
	const TEXT__X_PASCAL = 'text/x-pascal';
	const APPLICATION__PKCS10 = 'application/pkcs10';
	const APPLICATION__X_PKCS10 = 'application/x-pkcs10';
	const APPLICATION__PKCS_12 = 'application/pkcs-12';
	const APPLICATION__X_PKCS12 = 'application/x-pkcs12';
	const APPLICATION__X_PKCS7_SIGNATURE = 'application/x-pkcs7-signature';
	const APPLICATION__PKCS7_MIME = 'application/pkcs7-mime';
	const APPLICATION__X_PKCS7_MIME = 'application/x-pkcs7-mime';
	const APPLICATION__X_PKCS7_CERTREQRESP = 'application/x-pkcs7-certreqresp';
	const APPLICATION__PKCS7_SIGNATURE = 'application/pkcs7-signature';
	const APPLICATION__PRO_ENG = 'application/pro_eng';
	const TEXT__PASCAL = 'text/pascal';
	const IMAGE__X_PORTABLE_BITMAP = 'image/x-portable-bitmap';
	const APPLICATION__VND_HP_PCL = 'application/vnd.hp-pcl';
	const APPLICATION__X_PCL = 'application/x-pcl';
	const IMAGE__X_PICT = 'image/x-pict';
	const IMAGE__X_PCX = 'image/x-pcx';
	const CHEMICAL__X_PDB = 'chemical/x-pdb';
	const APPLICATION__PDF = 'application/pdf';
	const AUDIO__MAKE_MY_FUNK = 'audio/make.my.funk';
	const IMAGE__X_PORTABLE_GRAYMAP = 'image/x-portable-graymap';
	const IMAGE__X_PORTABLE_GREYMAP = 'image/x-portable-greymap';
	const IMAGE__PICT = 'image/pict';
	const APPLICATION__X_NEWTON_COMPATIBLE_PKG = 'application/x-newton-compatible-pkg';
	const APPLICATION__VND_MS_PKI_PKO = 'application/vnd.ms-pki.pko';
	const TEXT__X_SCRIPT_PERL = 'text/x-script.perl';
	const APPLICATION__X_PIXCLSCRIPT = 'application/x-pixclscript';
	const IMAGE__X_XPIXMAP = 'image/x-xpixmap';
	const TEXT__X_SCRIPT_PERL_MODULE = 'text/x-script.perl-module';
	const APPLICATION__X_PAGEMAKER = 'application/x-pagemaker';
	const IMAGE__PNG = 'image/png';
	const APPLICATION__X_PORTABLE_ANYMAP = 'application/x-portable-anymap';
	const IMAGE__X_PORTABLE_ANYMAP = 'image/x-portable-anymap';
	const APPLICATION__MSPOWERPOINT = 'application/mspowerpoint';
	const APPLICATION__VND_MS_POWERPOINT = 'application/vnd.ms-powerpoint';
	const MODEL__X_POV = 'model/x-pov';
	const IMAGE__X_PORTABLE_PIXMAP = 'image/x-portable-pixmap';
	const APPLICATION__POWERPOINT = 'application/powerpoint';
	const APPLICATION__X_MSPOWERPOINT = 'application/x-mspowerpoint';
	const APPLICATION__X_FREELANCE = 'application/x-freelance';
	const PALEOVU__X_PV = 'paleovu/x-pv';
	const TEXT__X_SCRIPT_PHYTON = 'text/x-script.phyton';
	const APPLICATION__X_BYTECODE_PYTHON = 'application/x-bytecode.python';
	const AUDIO__VND_QCELP = 'audio/vnd.qcelp';
	const IMAGE__X_QUICKTIME = 'image/x-quicktime';
	const VIDEO__X_QTC = 'video/x-qtc';
	const AUDIO__X_PN_REALAUDIO = 'audio/x-pn-realaudio';
	const AUDIO__X_PN_REALAUDIO_PLUGIN = 'audio/x-pn-realaudio-plugin';
	const AUDIO__X_REALAUDIO = 'audio/x-realaudio';
	const APPLICATION__X_CMU_RASTER = 'application/x-cmu-raster';
	const IMAGE__CMU_RASTER = 'image/cmu-raster';
	const IMAGE__X_CMU_RASTER = 'image/x-cmu-raster';
	const TEXT__X_SCRIPT_REXX = 'text/x-script.rexx';
	const IMAGE__VND_RN_REALFLASH = 'image/vnd.rn-realflash';
	const IMAGE__X_RGB = 'image/x-rgb';
	const APPLICATION__VND_RN_REALMEDIA = 'application/vnd.rn-realmedia';
	const AUDIO__MID = 'audio/mid';
	const APPLICATION__RINGING_TONES = 'application/ringing-tones';
	const APPLICATION__VND_NOKIA_RINGING_TONE = 'application/vnd.nokia.ringing-tone';
	const APPLICATION__VND_RN_REALPLAYER = 'application/vnd.rn-realplayer';
	const APPLICATION__X_TROFF = 'application/x-troff';
	const IMAGE__VND_RN_REALPIX = 'image/vnd.rn-realpix';
	const TEXT__RICHTEXT = 'text/richtext';
	const TEXT__VND_RN_REALTEXT = 'text/vnd.rn-realtext';
	const APPLICATION__RTF = 'application/rtf';
	const APPLICATION__X_RTF = 'application/x-rtf';
	const VIDEO__VND_RN_REALVIDEO = 'video/vnd.rn-realvideo';
	const AUDIO__S3M = 'audio/s3m';
	const APPLICATION__X_TBOOK = 'application/x-tbook';
	const APPLICATION__X_LOTUSSCREENCAM = 'application/x-lotusscreencam';
	const TEXT__X_SCRIPT_GUILE = 'text/x-script.guile';
	const TEXT__X_SCRIPT_SCHEME = 'text/x-script.scheme';
	const VIDEO__X_SCM = 'video/x-scm';
	const APPLICATION__SDP = 'application/sdp';
	const APPLICATION__X_SDP = 'application/x-sdp';
	const APPLICATION__SOUNDER = 'application/sounder';
	const APPLICATION__SEA = 'application/sea';
	const APPLICATION__X_SEA = 'application/x-sea';
	const APPLICATION__SET = 'application/set';
	const TEXT__SGML = 'text/sgml';
	const TEXT__X_SGML = 'text/x-sgml';
	const APPLICATION__X_SH = 'application/x-sh';
	const APPLICATION__X_SHAR = 'application/x-shar';
	const TEXT__X_SCRIPT_SH = 'text/x-script.sh';
	const TEXT__X_SERVER_PARSED_HTML = 'text/x-server-parsed-html';
	const AUDIO__X_PSID = 'audio/x-psid';
	const APPLICATION__X_SIT = 'application/x-sit';
	const APPLICATION__X_STUFFIT = 'application/x-stuffit';
	const APPLICATION__X_KOAN = 'application/x-koan';
	const APPLICATION__X_SEELOGO = 'application/x-seelogo';
	const APPLICATION__SMIL = 'application/smil';
	const AUDIO__X_ADPCM = 'audio/x-adpcm';
	const APPLICATION__SOLIDS = 'application/solids';
	const APPLICATION__X_PKCS7_CERTIFICATES = 'application/x-pkcs7-certificates';
	const TEXT__X_SPEECH = 'text/x-speech';
	const APPLICATION__FUTURESPLASH = 'application/futuresplash';
	const APPLICATION__X_SPRITE = 'application/x-sprite';
	const APPLICATION__X_WAIS_SOURCE = 'application/x-wais-source';
	const APPLICATION__STREAMINGMEDIA = 'application/streamingmedia';
	const APPLICATION__VND_MS_PKI_CERTSTORE = 'application/vnd.ms-pki.certstore';
	const APPLICATION__STEP = 'application/step';
	const APPLICATION__SLA = 'application/sla';
	const APPLICATION__VND_MS_PKI_STL = 'application/vnd.ms-pki.stl';
	const APPLICATION__X_NAVISTYLE = 'application/x-navistyle';
	const APPLICATION__X_SV4CPIO = 'application/x-sv4cpio';
	const APPLICATION__X_SV4CRC = 'application/x-sv4crc';
	const APPLICATION__X_WORLD = 'application/x-world';
	const X_WORLD__X_SVR = 'x-world/x-svr';
	const APPLICATION__X_SHOCKWAVE_FLASH = 'application/x-shockwave-flash';
	const APPLICATION__X_TAR = 'application/x-tar';
	const APPLICATION__TOOLBOOK = 'application/toolbook';
	const APPLICATION__X_TCL = 'application/x-tcl';
	const TEXT__X_SCRIPT_TCL = 'text/x-script.tcl';
	const TEXT__X_SCRIPT_TCSH = 'text/x-script.tcsh';
	const APPLICATION__X_TEX = 'application/x-tex';
	const APPLICATION__X_TEXINFO = 'application/x-texinfo';
	const APPLICATION__PLAIN = 'application/plain';
	const APPLICATION__GNUTAR = 'application/gnutar';
	const IMAGE__TIFF = 'image/tiff';
	const IMAGE__X_TIFF = 'image/x-tiff';
	const AUDIO__TSP_AUDIO = 'audio/tsp-audio';
	const APPLICATION__DSPTYPE = 'application/dsptype';
	const AUDIO__TSPLAYER = 'audio/tsplayer';
	const TEXT__TAB_SEPARATED_VALUES = 'text/tab-separated-values';
	const TEXT__X_UIL = 'text/x-uil';
	const TEXT__URI_LIST = 'text/uri-list';
	const APPLICATION__I_DEAS = 'application/i-deas';
	const APPLICATION__X_USTAR = 'application/x-ustar';
	const MULTIPART__X_USTAR = 'multipart/x-ustar';
	const TEXT__X_UUENCODE = 'text/x-uuencode';
	const APPLICATION__X_CDLINK = 'application/x-cdlink';
	const TEXT__X_VCALENDAR = 'text/x-vcalendar';
	const APPLICATION__VDA = 'application/vda';
	const VIDEO__VDO = 'video/vdo';
	const APPLICATION__GROUPWISE = 'application/groupwise';
	const VIDEO__VIVO = 'video/vivo';
	const VIDEO__VND_VIVO = 'video/vnd.vivo';
	const APPLICATION__VOCALTEC_MEDIA_DESC = 'application/vocaltec-media-desc';
	const APPLICATION__VOCALTEC_MEDIA_FILE = 'application/vocaltec-media-file';
	const AUDIO__VOC = 'audio/voc';
	const AUDIO__X_VOC = 'audio/x-voc';
	const VIDEO__VOSAIC = 'video/vosaic';
	const AUDIO__VOXWARE = 'audio/voxware';
	const AUDIO__X_TWINVQ_PLUGIN = 'audio/x-twinvq-plugin';
	const AUDIO__X_TWINVQ = 'audio/x-twinvq';
	const APPLICATION__X_VRML = 'application/x-vrml';
	const MODEL__VRML = 'model/vrml';
	const X_WORLD__X_VRML = 'x-world/x-vrml';
	const X_WORLD__X_VRT = 'x-world/x-vrt';
	const APPLICATION__X_VISIO = 'application/x-visio';
	const APPLICATION__WORDPERFECT6_0 = 'application/wordperfect6.0';
	const APPLICATION__WORDPERFECT6_1 = 'application/wordperfect6.1';
	const AUDIO__WAV = 'audio/wav';
	const AUDIO__X_WAV = 'audio/x-wav';
	const APPLICATION__X_QPRO = 'application/x-qpro';
	const IMAGE__VND_WAP_WBMP = 'image/vnd.wap.wbmp';
	const APPLICATION__VND_XARA = 'application/vnd.xara';
	const APPLICATION__X_123 = 'application/x-123';
	const WINDOWS__METAFILE = 'windows/metafile';
	const TEXT__VND_WAP_WML = 'text/vnd.wap.wml';
	const APPLICATION__VND_WAP_WMLC = 'application/vnd.wap.wmlc';
	const TEXT__VND_WAP_WMLSCRIPT = 'text/vnd.wap.wmlscript';
	const APPLICATION__VND_WAP_WMLSCRIPTC = 'application/vnd.wap.wmlscriptc';
	const APPLICATION__WORDPERFECT = 'application/wordperfect';
	const APPLICATION__X_WPWIN = 'application/x-wpwin';
	const APPLICATION__X_LOTUS = 'application/x-lotus';
	const APPLICATION__MSWRITE = 'application/mswrite';
	const APPLICATION__X_WRI = 'application/x-wri';
	const TEXT__SCRIPLET = 'text/scriplet';
	const APPLICATION__X_WINTALK = 'application/x-wintalk';
	const IMAGE__X_XBITMAP = 'image/x-xbitmap';
	const IMAGE__X_XBM = 'image/x-xbm';
	const IMAGE__XBM = 'image/xbm';
	const VIDEO__X_AMT_DEMORUN = 'video/x-amt-demorun';
	const XGL__DRAWING = 'xgl/drawing';
	const IMAGE__VND_XIFF = 'image/vnd.xiff';
	const APPLICATION__EXCEL = 'application/excel';
	const APPLICATION__X_EXCEL = 'application/x-excel';
	const APPLICATION__X_MSEXCEL = 'application/x-msexcel';
	const APPLICATION__VND_MS_EXCEL = 'application/vnd.ms-excel';
	const AUDIO__XM = 'audio/xm';
	const APPLICATION__XML = 'application/xml';
	const TEXT__XML = 'text/xml';
	const XGL__MOVIE = 'xgl/movie';
	const APPLICATION__X_VND_LS_XPIX = 'application/x-vnd.ls-xpix';
	const IMAGE__XPM = 'image/xpm';
	const VIDEO__X_AMT_SHOWRUN = 'video/x-amt-showrun';
	const IMAGE__X_XWD = 'image/x-xwd';
	const IMAGE__X_XWINDOWDUMP = 'image/x-xwindowdump';
	const APPLICATION__X_COMPRESS = 'application/x-compress';
	const APPLICATION__X_ZIP_COMPRESSED = 'application/x-zip-compressed';
	const APPLICATION__ZIP = 'application/zip';
	const MULTIPART__X_ZIP = 'multipart/x-zip';
	const TEXT__X_SCRIPT_ZSH = 'text/x-script.zsh';
	/**
	 * @var array
	 */
	protected static $suffix_map = array(
		'3gp' => self::VIDEO__3_GPP,
		'3dm' => self::X_WORLD__X_3DMF,
		'3dmf' => self::X_WORLD__X_3DMF,
		'a' => self::APPLICATION__OCTET_STREAM,
		'aab' => self::APPLICATION__X_AUTHORWARE_BIN,
		'aam' => self::APPLICATION__X_AUTHORWARE_MAP,
		'aas' => self::APPLICATION__X_AUTHORWARE_SEG,
		'abc' => self::TEXT__VND_ABC,
		'acgi' => self::TEXT__HTML,
		'afl' => self::VIDEO__ANIMAFLEX,
		'ai' => self::APPLICATION__POSTSCRIPT,
		'aif' => array(
			self::AUDIO__X_AIFF,
			self::AUDIO__AIFF,
		),
		'aifc' => array(
			self::AUDIO__X_AIFF,
			self::AUDIO__AIFF,
		),
		'aiff' => array(
			self::AUDIO__X_AIFF,
			self::AUDIO__AIFF,
		),
		'aim' => self::APPLICATION__X_AIM,
		'aip' => self::TEXT__X_AUDIOSOFT_INTRA,
		'ani' => self::APPLICATION__X_NAVI_ANIMATION,
		'aos' => self::APPLICATION__X_NOKIA_9000_COMMUNICATOR_ADD_ON_SOFTWARE,
		'aps' => self::APPLICATION__MIME,
		'arc' => self::APPLICATION__OCTET_STREAM,
		'arj' => array(
			self::APPLICATION__ARJ,
			self::APPLICATION__OCTET_STREAM
		),
		'asd' => self::VIDEO__X_MS_ASF,
		'art' => self::IMAGE__X_JG,
		'asf' => self::VIDEO__X_MS_ASF,
		'asn' => self::APPLICATION__ASTOUND,
		'asm' => self::TEXT__X_ASM,
		'asp' => self::TEXT__ASP,
		'asx' => array(
			self::VIDEO__X_MS_ASF,
			self::APPLICATION__X_MPLAYER2,
			self::VIDEO__X_MS_ASF_PLUGIN
		),
		'au' => array(
			self::AUDIO__BASIC,
			self::AUDIO__X_AU
		),
		'avi' => array(
			self::VIDEO__X_MSVIDEO,
			self::VIDEO__AVI,
			self::VIDEO__MSVIDEO,
			self::APPLICATION__X_TROFF_MSVIDEO,
		),
		'avs' => self::VIDEO__AVS_VIDEO,
		'bat' => self::TEXT__PLAIN,
		'bcpio' => self::APPLICATION__X_BCPIO,
		'bin' => array(
			self::APPLICATION__MAC_BINARY,
			self::APPLICATION__MACBINARY,
			self::APPLICATION__OCTET_STREAM,
			self::APPLICATION__X_BINARY,
			self::APPLICATION__X_MACBINARY,
		),
		'bm' => self::IMAGE__BMP,
		'bmp' => array(
			self::IMAGE__X_MS_BMP,
			self::IMAGE__BMP,
			self::IMAGE__X_WINDOWS_BMP,
		),
		'boo' => self::APPLICATION__BOOK,
		'book' => self::APPLICATION__BOOK,
		'boz' => self::APPLICATION__X_BZIP2,
		'bsh' => self::APPLICATION__X_BSH,
		'bz' => self::APPLICATION__X_BZIP,
		'bz2' => self::APPLICATION__X_BZIP2,
		'c' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_C,
		),
		'c++' => self::TEXT__PLAIN,
		'cat' => self::APPLICATION__VND_MS_PKI_SECCAT,
		'cc' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_C,
		),
		'ccad' => self::APPLICATION__CLARISCAD,
		'cco' => self::APPLICATION__X_COCOA,
		'cdf' => array(
			self::APPLICATION__X_CDF,
			self::APPLICATION__CDF,
			self::APPLICATION__X_NETCDF,
		),
		'cer' => array(
			self::APPLICATION__PKIX_CERT,
			self::APPLICATION__X_X509_CA_CERT,
		),
		'cha' => self::APPLICATION__X_CHAT,
		'chat' => self::APPLICATION__X_CHAT,
		'class' => array(
			self::APPLICATION__X_JAVA_APPLET,
			self::APPLICATION__JAVA,
			self::APPLICATION__JAVA_BYTE_CODE,
			self::APPLICATION__X_JAVA_CLASS,
		),
		'com' => array( self::APPLICATION__OCTET_STREAM, self::TEXT__PLAIN, ),
		'conf' => self::TEXT__PLAIN,
		'cpio' => self::APPLICATION__X_CPIO,
		'cpp' => self::TEXT__X_C,
		'cpt' => array(
			self::APPLICATION__MAC_COMPACTPRO,
			self::APPLICATION__X_COMPACTPRO,
			self::APPLICATION__X_CPT,
		),
		'crl' => array(
			self::APPLICATION__PKCS_CRL,
			self::APPLICATION__PKIX_CRL,
		),
		'crt' => array(
			self::APPLICATION__X_X509_CA_CERT,
			self::APPLICATION__PKIX_CERT,
			self::APPLICATION__X_X509_USER_CERT,
		),
		'csh' => array(
			self::APPLICATION__X_CSH,
			self::TEXT__X_SCRIPT_CSH,
		),
		'css' => array(
			self::TEXT__CSS,
			self::APPLICATION__X_POINTPLUS,
		),
		'cxx' => self::TEXT__PLAIN,
		'dcr' => self::APPLICATION__X_DIRECTOR,
		'deepv' => self::APPLICATION__X_DEEPV,
		'def' => self::TEXT__PLAIN,
		'der' => self::APPLICATION__X_X509_CA_CERT,
		'dif' => self::VIDEO__X_DV,
		'dir' => self::APPLICATION__X_DIRECTOR,
		'dl' => array(
			self::VIDEO__DL,
			self::VIDEO__X_DL,
		),
		'doc' => self::APPLICATION__MSWORD,
		'dot' => self::APPLICATION__MSWORD,
		'dp' => self::APPLICATION__COMMONGROUND,
		'drw' => self::APPLICATION__DRAFTING,
		'dump' => self::APPLICATION__OCTET_STREAM,
		'dv' => self::VIDEO__X_DV,
		'dvi' => self::APPLICATION__X_DVI,
		'dwf' => self::MODEL__VND_DWF,
		'dwg' => array(
			self::APPLICATION__ACAD,
			self::IMAGE__VND_DWG,
			self::IMAGE__X_DWG,
		),
		'dxf' => array(
			self::APPLICATION__DXF,
			self::IMAGE__VND_DWG,
			self::IMAGE__X_DWG,
		),
		'dxr' => self::APPLICATION__X_DIRECTOR,
		'el' => self::TEXT__X_SCRIPT_ELISP,
		'elc' => self::APPLICATION__X_ELC,
		'env' => self::APPLICATION__X_ENVOY,
		'eps' => self::APPLICATION__POSTSCRIPT,
		'es' => self::APPLICATION__X_ESREHBER,
		'etx' => self::TEXT__X_SETEXT,
		'evy' => array(
			self::APPLICATION__ENVOY,
			self::APPLICATION__X_ENVOY,
		),
		'exe' => self::APPLICATION__OCTET_STREAM,
		'f' => array(
			self::TEXT__PLAIN,
			'f' => self::TEXT__X_FORTRAN,
		),
		'flv' => self::VIDEO__X_FLV,
		'f77' => self::TEXT__X_FORTRAN,
		'f90' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_FORTRAN,
		),
		'fdf' => self::APPLICATION__VND_FDF,
		'fif' => array(
			self::APPLICATION__FRACTALS,
			self::IMAGE__FIF,
		),
		'fli' => array(
			self::VIDEO__FLI,
			self::VIDEO__X_FLI,
		),
		'flo' => self::IMAGE__FLORIAN,
		'flx' => self::TEXT__VND_FMI_FLEXSTOR,
		'fmf' => self::VIDEO__X_ATOMIC3D_FEATURE,
		'for' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_FORTRAN,
		),
		'fpx' => array(
			self::IMAGE__VND_FPX,
			self::IMAGE__VND_NET_FPX,
		),
		'frl' => self::APPLICATION__FREELOADER,
		'funk' => self::AUDIO__MAKE,
		'g' => self::TEXT__PLAIN,
		'g3' => self::IMAGE__G3FAX,
		'gif' => self::IMAGE__GIF,
		'gl' => array(
			self::VIDEO__GL,
			self::VIDEO__X_GL,
		),
		'gsd' => self::AUDIO__X_GSM,
		'gsm' => self::AUDIO__X_GSM,
		'gsp' => self::APPLICATION__X_GSP,
		'gss' => self::APPLICATION__X_GSS,
		'gtar' => self::APPLICATION__X_GTAR,
		'gz' => array(
			self::APPLICATION__X_COMPRESSED,
			self::APPLICATION__X_GZIP,
		),
		'gzip' => array(
			self::APPLICATION__X_GZIP,
			self::MULTIPART__X_GZIP,
		),
		'h' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_H,
		),
		'hdf' => self::APPLICATION__X_HDF,
		'help' => self::APPLICATION__X_HELPFILE,
		'hgl' => self::APPLICATION__VND_HP_HPGL,
		'hh' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_H,
		),
		'hlb' => self::TEXT__X_SCRIPT,
		'hlp' => array(
			self::APPLICATION__HLP,
			self::APPLICATION__X_HELPFILE,
			self::APPLICATION__X_WINHELP,
		),
		'hpg' => self::APPLICATION__VND_HP_HPGL,
		'hpgl' => self::APPLICATION__VND_HP_HPGL,
		'hqx' => array(
			self::APPLICATION__BINHEX,
			self::APPLICATION__BINHEX4,
			self::APPLICATION__MAC_BINHEX,
			self::APPLICATION__MAC_BINHEX40,
			self::APPLICATION__X_BINHEX40,
			self::APPLICATION__X_MAC_BINHEX40,
		),
		'hta' => self::APPLICATION__HTA,
		'htc' => self::TEXT__X_COMPONENT,
		'htm' => self::TEXT__HTML,
		'html' => self::TEXT__HTML,
		'htmls' => self::TEXT__HTML,
		'htt' => self::TEXT__WEBVIEWHTML,
		'htx' => self::TEXT__HTML,
		'ice' => self::X_CONFERENCE__X_COOLTALK,
		'ico' => self::IMAGE__X_ICON,
		'idc' => self::TEXT__PLAIN,
		'ief' => self::IMAGE__IEF,
		'iefs' => self::IMAGE__IEF,
		'iges' => array(
			self::APPLICATION__IGES,
			self::MODEL__IGES,
		),
		'igs' => array(
			self::APPLICATION__IGES,
			self::MODEL__IGES,
		),
		'ima' => self::APPLICATION__X_IMA,
		'imap' => self::APPLICATION__X_HTTPD_IMAP,
		'inf' => self::APPLICATION__INF,
		'ins' => self::APPLICATION__X_INTERNETT_SIGNUP,
		'ip' => self::APPLICATION__X_IP2,
		'isu' => self::VIDEO__X_ISVIDEO,
		'it' => self::AUDIO__IT,
		'iv' => self::APPLICATION__X_INVENTOR,
		'ivr' => self::I_WORLD__I_VRML,
		'ivy' => self::APPLICATION__X_LIVESCREEN,
		'jam' => self::AUDIO__X_JAM,
		'jav' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_JAVA_SOURCE,
		),
		'java' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_JAVA_SOURCE,
		),
		'jcm' => self::APPLICATION__X_JAVA_COMMERCE,
		'jfif' => array(
			self::IMAGE__JPEG,
			self::IMAGE__PJPEG,
		),
		'jfif-tbnl' => self::IMAGE__JPEG,
		'jpe' => array(
			self::IMAGE__JPEG,
			self::IMAGE__PJPEG,
		),
		'jpeg' => array(
			self::IMAGE__JPEG,
			self::IMAGE__PJPEG,
		),
		'jpg' => array(
			self::IMAGE__JPEG,
			self::IMAGE__PJPEG,
		),
		'jps' => self::IMAGE__X_JPS,
		'js' => array(
			self::APPLICATION__JAVASCRIPT,
			self::APPLICATION__X_JAVASCRIPT,
			self::APPLICATION__ECMASCRIPT,
			self::TEXT__JAVASCRIPT,
			self::TEXT__ECMASCRIPT,
		),
		'jut' => self::IMAGE__JUTVISION,
		'kar' => array(
			self::AUDIO__MIDI,
			self::MUSIC__X_KARAOKE,
		),
		'ksh' => array(
			self::APPLICATION__X_KSH,
			self::TEXT__X_SCRIPT_KSH,
		),
		'la' => array(
			self::AUDIO__NSPAUDIO,
			self::AUDIO__X_NSPAUDIO,
		),
		'lam' => self::AUDIO__X_LIVEAUDIO,
		'latex' => self::APPLICATION__X_LATEX,
		'lha' => array(
			self::APPLICATION__LHA,
			self::APPLICATION__OCTET_STREAM,
			self::APPLICATION__X_LHA,
		),
		'lhx' => self::APPLICATION__OCTET_STREAM,
		'list' => self::TEXT__PLAIN,
		'lma' => array(
			self::AUDIO__NSPAUDIO,
			self::AUDIO__X_NSPAUDIO,
		),
		'log' => self::TEXT__PLAIN,
		'lsp' => array(
			self::APPLICATION__X_LISP,
			self::TEXT__X_SCRIPT_LISP,
		),
		'lst' => self::TEXT__PLAIN,
		'lsx' => self::TEXT__X_LA_ASF,
		'ltx' => self::APPLICATION__X_LATEX,
		'lzh' => array(
			self::APPLICATION__OCTET_STREAM,
			self::APPLICATION__X_LZH,
		),
		'lzx' => array(
			self::APPLICATION__LZX,
			self::APPLICATION__OCTET_STREAM,
			self::APPLICATION__X_LZX,
		),
		'm' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_M,
		),
		'm1v' => self::VIDEO__MPEG,
		'm2a' => self::AUDIO__MPEG,
		'm2v' => self::VIDEO__MPEG,
		'm3u' => self::AUDIO__X_MPEQURL,
		'man' => self::APPLICATION__X_TROFF_MAN,
		'map' => self::APPLICATION__X_NAVIMAP,
		'mar' => self::TEXT__PLAIN,
		'mbd' => self::APPLICATION__MBEDLET,
		'mc$' => self::APPLICATION__X_MAGIC_CAP_PACKAGE_1_0,
		'mcd' => array(
			self::APPLICATION__MCAD,
			self::APPLICATION__X_MATHCAD,
		),
		'mcf' => array(
			self::IMAGE__VASA,
			self::TEXT__MCF,
		),
		'mcp' => self::APPLICATION__NETMC,
		'me' => self::APPLICATION__X_TROFF_ME,
		'mht' => self::MESSAGE__RFC822,
		'mhtml' => self::MESSAGE__RFC822,
		'mid' => array(
			self::AUDIO__MIDI,
			self::APPLICATION__X_MIDI,
			self::AUDIO__X_MID,
			self::AUDIO__X_MIDI,
			self::MUSIC__CRESCENDO,
			self::X_MUSIC__X_MIDI,
		),
		'midi' => array(
			self::AUDIO__MIDI,
			self::APPLICATION__X_MIDI,
			self::AUDIO__X_MID,
			self::AUDIO__X_MIDI,
			self::MUSIC__CRESCENDO,
			self::X_MUSIC__X_MIDI,
		),
		'mif' => array(
			self::APPLICATION__X_FRAME,
			self::APPLICATION__X_MIF,
		),
		'mime' => array(
			self::MESSAGE__RFC822,
			self::WWW__MIME,
		),
		'mjf' => self::AUDIO__X_VND_AUDIOEXPLOSION_MJUICEMEDIAFILE,
		'mjpg' => self::VIDEO__X_MOTION_JPEG,
		'mm' => array(
			self::APPLICATION__BASE64,
			self::APPLICATION__X_MEME,
		),
		'mme' => self::APPLICATION__BASE64,
		'mod' => array(
			self::AUDIO__MOD,
			self::AUDIO__X_MOD,
		),
		'moov' => self::VIDEO__QUICKTIME,
		'mov' => self::VIDEO__QUICKTIME,
		'movie' => self::VIDEO__X_SGI_MOVIE,
		'mp2' => array(
			self::AUDIO__MPEG,
			self::AUDIO__X_MPEG,
			self::VIDEO__MPEG,
			self::VIDEO__X_MPEG,
			self::VIDEO__X_MPEQ2A,
		),
		'mp3' => array(
			self::AUDIO__MPEG3,
			self::AUDIO__X_MPEG_3,
			self::VIDEO__MPEG,
			self::VIDEO__X_MPEG,
		),
		'mpa' => array(
			self::AUDIO__MPEG,
			self::VIDEO__MPEG,
		),
		'mp4' => self::VIDEO__MP_4,
		'm4v' => self::VIDEO__MP_4,
		'mv4' => self::VIDEO__MP_4,
		'mpc' => self::APPLICATION__X_PROJECT,
		'mpe' => self::VIDEO__MPEG,
		'mpeg' => self::VIDEO__MPEG,
		'mpg' => array(
			self::AUDIO__MPEG,
			self::VIDEO__MPEG,
		),
		'mpga' => self::AUDIO__MPEG,
		'mpp' => self::APPLICATION__VND_MS_PROJECT,
		'mpt' => self::APPLICATION__X_PROJECT,
		'mpv' => self::APPLICATION__X_PROJECT,
		'mpx' => self::APPLICATION__X_PROJECT,
		'mrc' => self::APPLICATION__MARC,
		'ms' => self::APPLICATION__X_TROFF_MS,
		'mv' => self::VIDEO__X_SGI_MOVIE,
		'my' => self::AUDIO__MAKE,
		'mzz' => self::APPLICATION__X_VND_AUDIOEXPLOSION_MZZ,
		'nap' => self::IMAGE__NAPLPS,
		'naplps' => self::IMAGE__NAPLPS,
		'nc' => self::APPLICATION__X_NETCDF,
		'ncm' => self::APPLICATION__VND_NOKIA_CONFIGURATION_MESSAGE,
		'nif' => self::IMAGE__X_NIFF,
		'niff' => self::IMAGE__X_NIFF,
		'nix' => self::APPLICATION__X_MIX_TRANSFER,
		'nsc' => self::APPLICATION__X_CONFERENCE,
		'nvd' => self::APPLICATION__X_NAVIDOC,
		'o' => self::APPLICATION__OCTET_STREAM,
		'oda' => self::APPLICATION__ODA,
		'omc' => self::APPLICATION__X_OMC,
		'omcd' => self::APPLICATION__X_OMCDATAMAKER,
		'omcr' => self::APPLICATION__X_OMCREGERATOR,
		'p' => self::TEXT__X_PASCAL,
		'p10' => array(
			self::APPLICATION__PKCS10,
			self::APPLICATION__X_PKCS10,
		),
		'p12' => array(
			self::APPLICATION__PKCS_12,
			self::APPLICATION__X_PKCS12,
		),
		'p7a' => self::APPLICATION__X_PKCS7_SIGNATURE,
		'p7c' => array(
			self::APPLICATION__PKCS7_MIME,
			self::APPLICATION__X_PKCS7_MIME,
		),
		'p7m' => array(
			self::APPLICATION__PKCS7_MIME,
			self::APPLICATION__X_PKCS7_MIME,
		),
		'p7r' => self::APPLICATION__X_PKCS7_CERTREQRESP,
		'p7s' => self::APPLICATION__PKCS7_SIGNATURE,
		'part' => self::APPLICATION__PRO_ENG,
		'pas' => self::TEXT__PASCAL,
		'pbm' => self::IMAGE__X_PORTABLE_BITMAP,
		'pcl' => array(
			self::APPLICATION__VND_HP_PCL,
			self::APPLICATION__X_PCL,
		),
		'pct' => self::IMAGE__X_PICT,
		'pcx' => self::IMAGE__X_PCX,
		'pdb' => self::CHEMICAL__X_PDB,
		'pdf' => self::APPLICATION__PDF,
		'pfunk' => array(
			self::AUDIO__MAKE,
			self::AUDIO__MAKE_MY_FUNK,
		),
		'pgm' => array(
			self::IMAGE__X_PORTABLE_GRAYMAP,
			self::IMAGE__X_PORTABLE_GREYMAP,
		),
		'pic' => self::IMAGE__PICT,
		'pict' => self::IMAGE__PICT,
		'pkg' => self::APPLICATION__X_NEWTON_COMPATIBLE_PKG,
		'pko' => self::APPLICATION__VND_MS_PKI_PKO,
		'pl' => array(
			self::TEXT__PLAIN,
			self::TEXT__X_SCRIPT_PERL,
		),
		'plx' => self::APPLICATION__X_PIXCLSCRIPT,
		'pm' => array(
			self::IMAGE__X_XPIXMAP,
			self::TEXT__X_SCRIPT_PERL_MODULE,
		),
		'pm4' => self::APPLICATION__X_PAGEMAKER,
		'pm5' => self::APPLICATION__X_PAGEMAKER,
		'png' => self::IMAGE__PNG,
		'pnm' => array(
			self::APPLICATION__X_PORTABLE_ANYMAP,
			self::IMAGE__X_PORTABLE_ANYMAP,
		),
		'pot' => array(
			self::APPLICATION__MSPOWERPOINT,
			self::APPLICATION__VND_MS_POWERPOINT,
		),
		'pov' => self::MODEL__X_POV,
		'ppa' => self::APPLICATION__VND_MS_POWERPOINT,
		'ppm' => self::IMAGE__X_PORTABLE_PIXMAP,
		'pps' => array(
			self::APPLICATION__MSPOWERPOINT,
			self::APPLICATION__VND_MS_POWERPOINT,
		),
		'ppt' => array(
			self::APPLICATION__MSPOWERPOINT,
			self::APPLICATION__POWERPOINT,
			self::APPLICATION__VND_MS_POWERPOINT,
			self::APPLICATION__X_MSPOWERPOINT,
		),
		'ppz' => self::APPLICATION__MSPOWERPOINT,
		'pre' => self::APPLICATION__X_FREELANCE,
		'prt' => self::APPLICATION__PRO_ENG,
		'ps' => self::APPLICATION__POSTSCRIPT,
		'psd' => self::APPLICATION__OCTET_STREAM,
		'pvu' => self::PALEOVU__X_PV,
		'pwz' => self::APPLICATION__VND_MS_POWERPOINT,
		'py' => self::TEXT__X_SCRIPT_PHYTON,
		'pyc' => self::APPLICATION__X_BYTECODE_PYTHON,
		'qcp' => self::AUDIO__VND_QCELP,
		'qd3' => self::X_WORLD__X_3DMF,
		'qd3d' => self::X_WORLD__X_3DMF,
		'qif' => self::IMAGE__X_QUICKTIME,
		'qt' => self::VIDEO__QUICKTIME,
		'qtc' => self::VIDEO__X_QTC,
		'qti' => self::IMAGE__X_QUICKTIME,
		'qtif' => self::IMAGE__X_QUICKTIME,
		'ra' => array(
			self::AUDIO__X_PN_REALAUDIO,
			self::AUDIO__X_PN_REALAUDIO_PLUGIN,
			self::AUDIO__X_REALAUDIO,
		),
		'ram' => self::AUDIO__X_PN_REALAUDIO,
		'ras' => array(
			self::APPLICATION__X_CMU_RASTER,
			self::IMAGE__CMU_RASTER,
			self::IMAGE__X_CMU_RASTER,
		),
		'rast' => self::IMAGE__CMU_RASTER,
		'rexx' => self::TEXT__X_SCRIPT_REXX,
		'rf' => self::IMAGE__VND_RN_REALFLASH,
		'rgb' => self::IMAGE__X_RGB,
		'rm' => array(
			self::APPLICATION__VND_RN_REALMEDIA,
			self::AUDIO__X_PN_REALAUDIO,
		),
		'rmi' => self::AUDIO__MID,
		'rmm' => self::AUDIO__X_PN_REALAUDIO,
		'rmp' => array(
			self::AUDIO__X_PN_REALAUDIO,
			self::AUDIO__X_PN_REALAUDIO_PLUGIN,
		),
		'rng' => array(
			self::APPLICATION__RINGING_TONES,
			self::APPLICATION__VND_NOKIA_RINGING_TONE,
		),
		'rnx' => self::APPLICATION__VND_RN_REALPLAYER,
		'roff' => self::APPLICATION__X_TROFF,
		'rp' => self::IMAGE__VND_RN_REALPIX,
		'rpm' => self::AUDIO__X_PN_REALAUDIO_PLUGIN,
		'rt' => array(
			self::TEXT__RICHTEXT,
			self::TEXT__VND_RN_REALTEXT,
		),
		'rtf' => array(
			self::APPLICATION__RTF,
			self::APPLICATION__X_RTF,
			self::TEXT__RICHTEXT,
		),
		'rtx' => array(
			self::APPLICATION__RTF,
			self::TEXT__RICHTEXT,
		),
		'rv' => self::VIDEO__VND_RN_REALVIDEO,
		's' => self::TEXT__X_ASM,
		's3m' => self::AUDIO__S3M,
		'saveme' => self::APPLICATION__OCTET_STREAM,
		'sbk' => self::APPLICATION__X_TBOOK,
		'scm' => array(
			self::APPLICATION__X_LOTUSSCREENCAM,
			self::TEXT__X_SCRIPT_GUILE,
			self::TEXT__X_SCRIPT_SCHEME,
			self::VIDEO__X_SCM,
		),
		'sdml' => self::TEXT__PLAIN,
		'sdp' => array(
			self::APPLICATION__SDP,
			self::APPLICATION__X_SDP,
		),
		'sdr' => self::APPLICATION__SOUNDER,
		'sea' => array(
			self::APPLICATION__SEA,
			self::APPLICATION__X_SEA,
		),
		'set' => self::APPLICATION__SET,
		'sgm' => array(
			self::TEXT__SGML,
			self::TEXT__X_SGML,
		),
		'sgml' => array(
			self::TEXT__SGML,
			self::TEXT__X_SGML,
		),
		'sh' => array(
			self::APPLICATION__X_BSH,
			self::APPLICATION__X_SH,
			self::APPLICATION__X_SHAR,
			self::TEXT__X_SCRIPT_SH,
		),
		'shar' => array(
			self::APPLICATION__X_BSH,
			self::APPLICATION__X_SHAR,
		),
		'shtml' => array(
			self::TEXT__HTML,
			self::TEXT__X_SERVER_PARSED_HTML,
		),
		'sid' => self::AUDIO__X_PSID,
		'sit' => array(
			self::APPLICATION__X_SIT,
			self::APPLICATION__X_STUFFIT,
		),
		'skd' => self::APPLICATION__X_KOAN,
		'skm' => self::APPLICATION__X_KOAN,
		'skp' => self::APPLICATION__X_KOAN,
		'skt' => self::APPLICATION__X_KOAN,
		'sl' => self::APPLICATION__X_SEELOGO,
		'smi' => self::APPLICATION__SMIL,
		'smil' => self::APPLICATION__SMIL,
		'snd' => array(
			self::AUDIO__BASIC,
			self::AUDIO__X_ADPCM,
		),
		'sol' => self::APPLICATION__SOLIDS,
		'spc' => array(
			self::APPLICATION__X_PKCS7_CERTIFICATES,
			self::TEXT__X_SPEECH,
		),
		'ogg' => self::APPLICATION__OGG,
		'oga' => self::AUDIO__OGG,
		'ogv' => self::VIDEO__OGG,
		'spl' => self::APPLICATION__FUTURESPLASH,
		'spr' => self::APPLICATION__X_SPRITE,
		'sprite' => self::APPLICATION__X_SPRITE,
		'src' => self::APPLICATION__X_WAIS_SOURCE,
		'ssi' => self::TEXT__X_SERVER_PARSED_HTML,
		'ssm' => self::APPLICATION__STREAMINGMEDIA,
		'sst' => self::APPLICATION__VND_MS_PKI_CERTSTORE,
		'step' => self::APPLICATION__STEP,
		'stl' => array(
			self::APPLICATION__SLA,
			self::APPLICATION__VND_MS_PKI_STL,
			self::APPLICATION__X_NAVISTYLE,
		),
		'stp' => self::APPLICATION__STEP,
		'sv4cpio' => self::APPLICATION__X_SV4CPIO,
		'sv4crc' => self::APPLICATION__X_SV4CRC,
		'svf' => array(
			self::IMAGE__VND_DWG,
			self::IMAGE__X_DWG,
		),
		'svr' => array(
			self::APPLICATION__X_WORLD,
			self::X_WORLD__X_SVR,
		),
		'swf' => self::APPLICATION__X_SHOCKWAVE_FLASH,
		't' => self::APPLICATION__X_TROFF,
		'talk' => self::TEXT__X_SPEECH,
		'tar' => self::APPLICATION__X_TAR,
		'tbk' => array(
			self::APPLICATION__TOOLBOOK,
			self::APPLICATION__X_TBOOK,
		),
		'tcl' => array(
			self::APPLICATION__X_TCL,
			self::TEXT__X_SCRIPT_TCL,
		),
		'tcsh' => self::TEXT__X_SCRIPT_TCSH,
		'tex' => self::APPLICATION__X_TEX,
		'texi' => self::APPLICATION__X_TEXINFO,
		'texinfo' => self::APPLICATION__X_TEXINFO,
		'text' => array(
			self::TEXT__PLAIN,
			self::APPLICATION__PLAIN,
		),
		'tgz' => array(
			self::APPLICATION__GNUTAR,
			self::APPLICATION__X_COMPRESSED,
		),
		'tif' => array(
			self::IMAGE__TIFF,
			self::IMAGE__X_TIFF,
		),
		'tiff' => array(
			self::IMAGE__TIFF,
			self::IMAGE__X_TIFF,
		),
		'tr' => self::APPLICATION__X_TROFF,
		'tsi' => self::AUDIO__TSP_AUDIO,
		'tsp' => array(
			self::APPLICATION__DSPTYPE,
			self::AUDIO__TSPLAYER,
		),
		'tsv' => self::TEXT__TAB_SEPARATED_VALUES,
		'turbot' => self::IMAGE__FLORIAN,
		'txt' => self::TEXT__PLAIN,
		'uil' => self::TEXT__X_UIL,
		'uni' => self::TEXT__URI_LIST,
		'unis' => self::TEXT__URI_LIST,
		'unv' => self::APPLICATION__I_DEAS,
		'uri' => self::TEXT__URI_LIST,
		'uris' => self::TEXT__URI_LIST,
		'ustar' => array(
			self::APPLICATION__X_USTAR,
			self::MULTIPART__X_USTAR,
		),
		'uu' => array(
			self::APPLICATION__OCTET_STREAM,
			self::TEXT__X_UUENCODE,
		),
		'uue' => self::TEXT__X_UUENCODE,
		'vcd' => self::APPLICATION__X_CDLINK,
		'vcs' => self::TEXT__X_VCALENDAR,
		'vda' => self::APPLICATION__VDA,
		'vdo' => self::VIDEO__VDO,
		'vew' => self::APPLICATION__GROUPWISE,
		'viv' => array(
			self::VIDEO__VIVO,
			self::VIDEO__VND_VIVO,
		),
		'vivo' => array(
			self::VIDEO__VIVO,
			self::VIDEO__VND_VIVO,
		),
		'vmd' => self::APPLICATION__VOCALTEC_MEDIA_DESC,
		'vmf' => self::APPLICATION__VOCALTEC_MEDIA_FILE,
		'voc' => array(
			self::AUDIO__VOC,
			self::AUDIO__X_VOC,
		),
		'vos' => self::VIDEO__VOSAIC,
		'vox' => self::AUDIO__VOXWARE,
		'vqe' => self::AUDIO__X_TWINVQ_PLUGIN,
		'vqf' => self::AUDIO__X_TWINVQ,
		'vql' => self::AUDIO__X_TWINVQ_PLUGIN,
		'vrml' => array(
			self::APPLICATION__X_VRML,
			self::MODEL__VRML,
			self::X_WORLD__X_VRML,
		),
		'vrt' => self::X_WORLD__X_VRT,
		'vsd' => self::APPLICATION__X_VISIO,
		'vst' => self::APPLICATION__X_VISIO,
		'vsw' => self::APPLICATION__X_VISIO,
		'w60' => self::APPLICATION__WORDPERFECT6_0,
		'w61' => self::APPLICATION__WORDPERFECT6_1,
		'w6w' => self::APPLICATION__MSWORD,
		'wav' => array(
			self::AUDIO__X_WAV,
			self::AUDIO__WAV,
		),
		'wb1' => self::APPLICATION__X_QPRO,
		'wbmp' => self::IMAGE__VND_WAP_WBMP,
		'web' => self::APPLICATION__VND_XARA,
		'wiz' => self::APPLICATION__MSWORD,
		'wk1' => self::APPLICATION__X_123,
		'wmf' => self::WINDOWS__METAFILE,
		'wml' => self::TEXT__VND_WAP_WML,
		'wmlc' => self::APPLICATION__VND_WAP_WMLC,
		'wmls' => self::TEXT__VND_WAP_WMLSCRIPT,
		'wmlsc' => self::APPLICATION__VND_WAP_WMLSCRIPTC,
		'word' => self::APPLICATION__MSWORD,
		'wp' => self::APPLICATION__WORDPERFECT,
		'wp5' => array(
			self::APPLICATION__WORDPERFECT,
			self::APPLICATION__WORDPERFECT6_0,
		),
		'wm' => self::VIDEO__X_MS_WM,
		'wma' => self::AUDIO__X_MS_WMA,
		'wmd' => self::VIDEO__X_MS_WMD,
		'wmv' => self::VIDEO__X_MS_WMV,
		'wmx' => self::VIDEO__X_MS_WMX,
		'wmz' => self::VIDEO__X_MS_WMZ,
		'wvx' => self::VIDEO__X_MS_WVX,
		'wp6' => self::APPLICATION__WORDPERFECT,
		'wpd' => array(
			self::APPLICATION__WORDPERFECT,
			self::APPLICATION__X_WPWIN,
		),
		'wq1' => self::APPLICATION__X_LOTUS,
		'wri' => array(
			self::APPLICATION__MSWRITE,
			self::APPLICATION__X_WRI,
		),
		'wrl' => array(
			self::APPLICATION__X_WORLD,
			self::MODEL__VRML,
			self::X_WORLD__X_VRML,
		),
		'wrz' => array(
			self::MODEL__VRML,
			self::X_WORLD__X_VRML,
		),
		'wsc' => self::TEXT__SCRIPLET,
		'wsrc' => self::APPLICATION__X_WAIS_SOURCE,
		'wtk' => self::APPLICATION__X_WINTALK,
		'xbm' => array(
			self::IMAGE__X_XBITMAP,
			self::IMAGE__X_XBM,
			self::IMAGE__XBM,
		),
		'xdr' => self::VIDEO__X_AMT_DEMORUN,
		'xgz' => self::XGL__DRAWING,
		'xif' => self::IMAGE__VND_XIFF,
		'xl' => self::APPLICATION__EXCEL,
		'xla' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__X_EXCEL,
			self::APPLICATION__X_MSEXCEL,
		),
		'xlb' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlc' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xld' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlk' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xll' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlm' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlsx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_SPREADSHEETML_SHEET,
		'xltx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_SPREADSHEETML_TEMPLATE,
		'potx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_TEMPLATE,
		'ppsx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_SLIDESHOW,
		'pptx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_PRESENTATION,
		'sldx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_PRESENTATIONML_SLIDE,
		'docx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_WORDPROCESSINGML_DOCUMENT,
		'dotx' => self::APPLICATION__VND_OPENXMLFORMATS_OFFICEDOCUMENT_WORDPROCESSINGML_TEMPLATE,
		'xlam' => self::APPLICATION__VND_MS_EXCEL_ADDIN_MACRO_ENABLED_12,
		'xlsb' => self::APPLICATION__VND_MS_EXCEL_SHEET_BINARY_MACRO_ENABLED_12,
		'xls' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
			self::APPLICATION__X_MSEXCEL,
		),
		'xlt' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlv' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__X_EXCEL,
		),
		'xlw' => array(
			self::APPLICATION__EXCEL,
			self::APPLICATION__VND_MS_EXCEL,
			self::APPLICATION__X_EXCEL,
			self::APPLICATION__X_MSEXCEL,
		),
		'xm' => self::AUDIO__XM,
		'xml' => array(
			self::TEXT__XML,
			self::APPLICATION__XML,
		),
		'xmz' => self::XGL__MOVIE,
		'xpix' => self::APPLICATION__X_VND_LS_XPIX,
		'xpm' => array(
			self::IMAGE__XPM,
			self::IMAGE__X_XPIXMAP,
		),
		'x-png' => self::IMAGE__PNG,
		'xsr' => self::VIDEO__X_AMT_SHOWRUN,
		'xwd' => array(
			self::IMAGE__X_XWD,
			self::IMAGE__X_XWINDOWDUMP,
		),
		'xyz' => self::CHEMICAL__X_PDB,
		'z' => array(
			self::APPLICATION__X_COMPRESS,
			self::APPLICATION__X_COMPRESSED,
		),
		'zip' => array(
			self::APPLICATION__ZIP,
			self::APPLICATION__X_COMPRESSED,
			self::APPLICATION__X_ZIP_COMPRESSED,
			self::MULTIPART__X_ZIP,
		),
		'zoo' => self::APPLICATION__OCTET_STREAM,
		'zsh' => self::TEXT__X_SCRIPT_ZSH,
		'youtube' => self::VIDEO__YOUTUBE,
		'vimeo' => self::VIDEO__VIMEO,
		'xsl' => self::APPLICATION__XML,
	);
	/**
	 * @var string
	 */
	protected $path = '';
	/**
	 * @var string
	 */
	protected $suffix = '';
	/**
	 * @var bool
	 */
	protected $external = false;


	/**
	 * mime constructor.
	 */
	protected function __construct($path_to_file) {
		if (strpos($path_to_file, 'http://') || strpos($path_to_file, 'https://')) {
			$this->setExternal(true);
		}
		$parts = parse_url($path_to_file);
		$this->setPath($path_to_file);
		$this->setSuffix(pathinfo($parts['path'], PATHINFO_EXTENSION));
	}


	/**
	 * @return array
	 */
	public static function getExt2MimeMap() {
		$map = array();
		foreach (self::$suffix_map as $k => $v) {
			if (is_array($v)) {
				$type = $v[0];
			} else {
				$type = $v;
			}
			$map['.' . $k] = $type;
		}

		return $map;
	}


	/**
	 * @param string $a_file
	 * @param string $a_filename
	 * @param string $a_mime
	 *
	 * @return string
	 * @deprecated use ilMimeTypeUtil::lookupMimeType() instead
	 */
	public static function getMimeType($a_file = '', $a_filename = '', $a_mime = '') {
		$path = '';
		if ($a_filename) {
			$path = $a_filename;
		} elseif ($a_file) {
			$path = $a_file;
		}

		return self::lookupMimeType($path, $a_mime);
	}


	/**
	 * @param        $path_to_file
	 * @param string $fallback
	 *
	 * @return string
	 */
	public static function lookupMimeType($path_to_file, $fallback = self::APPLICATION__OCTET_STREAM) {
		$obj = new self($path_to_file);

		return $obj->get($fallback);
	}


	/**
	 * @param string $fallback
	 *
	 * @return string
	 */
	public function get($fallback = self::APPLICATION__OCTET_STREAM) {
		if ($this->isExternal()) {
			if (is_int(strpos($this->getPath(), 'youtube.'))) {
				return self::VIDEO__YOUTUBE;
			}
			if (is_int(strpos($this->getPath(), 'vimeo.'))) {
				return self::VIDEO__VIMEO;
			}
		}

		if ($this->getSuffix()) {
			if (isset(self::$suffix_map[$this->getSuffix()])) {
				if (! is_array(self::$suffix_map[$this->getSuffix()])) {
					return self::$suffix_map[$this->getSuffix()];
				} else {
					self::$suffix_map[$this->getSuffix()][0];
				}
			}
		}

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$info = finfo_file($finfo, $this->getPath());
		finfo_close($finfo);
		if ($info) {
			return $info;
		}

		return $fallback;
	}


	/**
	 * @return boolean
	 */
	public function isExternal() {
		return $this->external;
	}


	/**
	 * @param boolean $external
	 */
	public function setExternal($external) {
		$this->external = $external;
	}


	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}


	/**
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}


	/**
	 * @return string
	 */
	public function getSuffix() {
		return $this->suffix;
	}


	/**
	 * @param string $suffix
	 */
	public function setSuffix($suffix) {
		$this->suffix = $suffix;
	}
}

?>
