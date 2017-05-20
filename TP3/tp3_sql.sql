drop database if exists karengeg_tp3_db;

CREATE DATABASE karengeg_tp3_db;
USE karengeg_tp3_db;
-- --------------------------------------------------------
drop table if exists genericTable;
drop table if exists GetDocument;
drop table if exists SearchDocuments;
drop table if exists UpdateDocuments;
drop table if exists SetDocuments;
drop table if exists GetDocumentResSpace;

--
-- Structure de la table `genericTable`
--

CREATE TABLE  `genericTable` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `dateTime` datetime(4) NOT NULL,
  `SessionId` int(6) NOT NULL,
  `UserName` varchar (15) NOT NULL,
  `functionName` varchar(20) NOT NULL,
  `Docbase` varchar(10) NOT NULL,
   `TimeElapsed` TIME(4) NOT NULL,
   `ThreadId` int(4) NOT NULL,
   	`Memory` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ;

--
-- Structure de la table `GetDocument`
--

CREATE TABLE `GetDocument` LIKE `genericTable`;
ALTER TABLE GetDocument
ADD column DocSize int(10) NOT NULL;

--
-- Structure de la table `SearchDocuments`
--

CREATE TABLE `SearchDocuments` LIKE `genericTable`;
ALTER TABLE SearchDocuments
ADD column	RSCount	int(10)  NOT NULL,
ADD column	TimeSeekRead  decimal(10,3)  NOT NULL,
ADD column	PageCache  varchar(10) DEFAULT NULL,
ADD column	Parse	decimal(10,3)  NOT NULL,
ADD column	Typee  decimal(10,3) NOT NULL,
ADD column	Transform	decimal(10,3)  NOT NULL,
ADD column	Validate decimal(10,3)  NOT NULL,	
ADD column	EvalOrder decimal(10,3)   NOT NULL,	
ADD column	Evaluate  decimal(10,3)  NOT NULL,	
ADD column	ExcludeList	decimal(10,3)  NOT NULL,
ADD column	Sort decimal(10,3)   NOT NULL,
ADD column	InitRS	decimal(10,3)   NOT NULL,
ADD column  LogQuery	decimal(10,3)   NOT NULL,
ADD column	Retval  int(10)  NOT NULL,	
ADD column	Query  longtext  NOT NULL;

--
-- Structure de la table `UpdateDocuments`
--

CREATE TABLE `UpdateDocuments` LIKE `genericTable`;
ALTER TABLE UpdateDocuments
ADD column DocCount  int(10) NOT NULL,
ADD column	TotalSize  int(10)  NOT NULL,
ADD column	SyncExtractCount  int(10)  NOT NULL,	
ADD column	SyncExtractTime  time(4)  NOT NULL,	
ADD column	ProcessSyncIndexCount int(10)  NOT NULL,		
ADD column	ProcessSyncIndexTime time(4)  NOT NULL,	
ADD column	UpDocsInternalCount int(10)  NOT NULL,	
ADD column	UpDocsInternalTime time(4)  NOT NULL,	
ADD column	SyncCPCount int(10)  NOT NULL,	
ADD column	SyncCPTime time(4)  NOT NULL,	
ADD column	RemSummariesCount int(10) NOT NULL,	
ADD column	RemSummariesTime time(4)  NOT NULL,	
ADD column	CheckUniqueCount int(10)  NOT NULL,	
ADD column	CheckUniqueTime time(4)  NOT NULL,	
ADD column	UpdateRepoCount int(10)  NOT NULL,	
ADD column	UpdateRepoTime time(4)  NOT NULL,	
ADD column	UpdateStoreCount  int(10)  NOT NULL,	
ADD column	UpdateStoreTime time(4)  NOT NULL,	
ADD column	GetBPlusCount  int(10)  NOT NULL,	
ADD column	GetBPlusTime time(4)  NOT NULL,	
ADD column	AddBPlusCount int(10)  NOT NULL,	
ADD column	AddBPlusTime time(4)  NOT NULL,	
ADD column	RemStoreCount	int(10)  NOT NULL,
ADD column	RemStoreTime time(4)  NOT NULL,	
ADD column	UnusedMutexCount int(10)  NOT NULL,	
ADD column	UnusedMutexTime time(4)  NOT NULL,	
ADD column	PageCacheMutexCount  int(10)  NOT NULL,	
ADD column	PageCacheMutexTime  time (4)  NOT NULL,	
ADD column	SeekReadCount int(10) NOT NULL,	
ADD column	SeekReadTime   time(4)  NOT NULL,	
ADD column	SchedulerMutexCount  int(10)  NOT NULL,		
ADD column	SchedulerMutexTime  time (4) NOT NULL,
ADD column	SchedulerTotalCount  int(10),	
ADD column	SchedulerTotalTime	time(4)  NOT NULL,
ADD column	DocNames  text  NOT NULL;

--
-- Structure de la table `SetDocuments`
--
CREATE TABLE `SetDocuments` LIKE `genericTable`;
ALTER TABLE SetDocuments
ADD column DocCount  int(10) NOT NULL,
ADD column	TotalSize  int(10)   NOT NULL,
ADD column	SyncExtractCount  int(10)  NOT NULL,	
ADD column	SyncExtractTime time(4)  NOT NULL,
ADD column	SyncIdxAddCount int(10)  NOT NULL,	
ADD column	SyncIdxAddTime time(4)  NOT NULL,	
ADD column	SyncIdxRemCount int(10)  NOT NULL,	
ADD column	SyncIdxRemTime time(4)  NOT NULL,	
ADD column	ProcessSyncIdxCount int  NOT NULL,	
ADD column	ProcessSyncIdxTime	time(4)   NOT NULL,
ADD column	ProcessSyncPropCount	int(10)  NOT NULL,
ADD column	ProcessSyncPropTime	time(4)  NOT NULL,
ADD column	AddToExclListCount   int(10)  NOT NULL,
ADD column	AddToExclListTime  time(4)  NOT NULL,	
ADD column	DocNames text NOT NULL;
	 
--
-- Structure de la table `GetDocumentResSpace`
--
CREATE TABLE `GetDocumentResSpace` LIKE `genericTable`;
ALTER TABLE GetDocumentResSpace
ADD column DocCount  int(10) NOT NULL, 
ADD column	TotalSize  int(10)   NOT NULL; 