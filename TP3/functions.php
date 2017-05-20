<?php
    
    function quote( $string)
    {
        return "'".$string ."'";
    }
    
    abstract class FunctionBase 
    {
        protected $TableName;

        public $DateTime;
        
        public $SessionId;
        
        public $Username;
        
        public $FunctionName;
        
        public $DocBase;
        
        public $TimeElapsed;
        
        public $ThreadId;
        
        public $Memory;
        
        
        public function __construct($tableName) {
            $this->TableName = $tableName;
        }
        
        
        public  function parseFromLine($lineArray)
        {       
            $this->DateTime = $lineArray[0];       
            $this->SessionId = $lineArray[1];
            $this->Username = $lineArray[2];
            $this->FunctionName = $lineArray[3];
            $this->DocBase = $lineArray[4];
            $this->TimeElapsed = $lineArray[5];
            $this->ThreadId = $lineArray[6];
            $this->Memory = $lineArray[7];
              
        }
        
        public function prettyPrint()
        {
            $prettyPrint = "<p>Datetime: ". $this->DateTime.
                           " ||| Function Name: ". $this->FunctionName.
                           " ||| Doc Base: ". $this->DocBase
                           ;
            
            return $prettyPrint;
        }
        
       
        public static function createFunction($line)
        {
            $lineArray = explode("\t", $line);           
            $functionName = $lineArray[3];
 			
            
            if( strcmp($functionName,"GetDocumentResSpace") == 0)
            {
                $function = new GetDocumentResSpace("GetDocumentResSpace");
                $function->parseFromLine($lineArray);

            }
            else if(strcmp($functionName,"SearchDocuments") == 0)
            {
                $function = new SearchDocuments("SearchDocuments");
                $function->parseFromLine($lineArray);
                              
            }
          
            else if(strcmp($functionName,"GetDocument") == 0)
            {
                $function = new GetDocument("GetDocument");
                $function->parseFromLine($lineArray);
            }              
            else if(strcmp($functionName,"UpdateDocuments") == 0)
            {
                $function = new UpdateDocuments("UpdateDocuments");
                $function->parseFromLine($lineArray);
            }
            else if(strcmp($functionName,"SetDocuments") == 0)
            {
                $function = new SetDocuments("SetDocuments");
                $function->parseFromLine($lineArray);
           }     
            else
            {
                $function = 0;
            }
           
            return $function;         
        }
        
        //Retourne les colonnes dans lesquels on veut insérer
        protected function createSqlColumnsQuery()
        {
            $columns = "dateTime, SessionId, UserName, functionName, Docbase, TimeElapsed, ThreadId, Memory";
            return $columns;           
        }

        //Retourne les valeurs à insérer
        protected function createSqlValuesQuery()
        {
            $values = quote($this->DateTime) . ", " . quote($this->SessionId) . ", " . quote($this->Username) .
                    ", " . quote($this->FunctionName) . ", " . quote($this->DocBase) . ", " . quote($this->TimeElapsed) . 
                    ", " . quote($this->ThreadId) . ", " . quote($this->Memory) ;
            
            return $values;
                    
        }
        
        //Retourne la requête pour insérer dans la table
        public function createSqlQuery()
        {
            $query = "INSERT INTO " . $this->TableName  . "(" . $this->createSqlColumnsQuery() . ")"
                    . " VALUES (" . $this->createSqlValuesQuery() . ")";
            
            return $query;
        }
        public function get_TableName()
        {
        	return $this->TableName;
        }
        
         public function get_UserName()
        {
        	return $this->UserName;
        }
       // public function Test()
       // {
           //if($this->TableName == "SearchDocuments") {
           
        	//$query = "SELECT  TimeElapsed   FROM " . $this->TableName . ";" ;
        	
        	//return $query;
        	//}
        	
        //}
        
    }
    
    class SearchDocuments extends FunctionBase
    {
        public $RSCount;
        public $TimeSeekRead;
        public $PageCache;
        public $Parse;
        public $Typee;
        public $Transform;
        public $Validate;
        public $EvalOrder;
        public $Evaluate;
        public $ExcludeList;
        public $Sort;
        public $InitRS;
        public $LogQuery;
        public $Retval;
        public $Query;
        
        public function parseFromLine($lineArray) {
            parent::parseFromLine($lineArray);
            $this->RSCount = $lineArray[8];
            $this->TimeSeekRead = $lineArray[9];
            $this->Page = $lineArray[10];
            $this->Parse = $lineArray[11];
            $this->Typee = $lineArray[12];
            $this->Transform = $lineArray[13];
            $this->Validate = $lineArray[14];
            $this->EvalOrder = $lineArray[15];
            $this->Evaluate = $lineArray[16];
            $this->ExcludeList = $lineArray[17];
            $this->Sort = $lineArray[18];
            $this->InitRS = $lineArray[19];
            $this->LogQuery = $lineArray[20];
            $this->Retval = $lineArray[21];
            $this->Query = $lineArray[22]; 
                  
        }
        public function prettyPrint()
        {
           $prettyPrint = parent::prettyPrint().
                   " ||| RSCount: " . $this->RSCount.
                   " ||| Query: " . $this->Query;
           
           return $prettyPrint;
        }
        
        protected function createSqlColumnsQuery() {
            $columns = parent::createSqlColumnsQuery() . ", RSCount , TimeSeekRead, PageCache, " .
                     "Parse, Typee, Transform,  Validate, EvalOrder, Evaluate, ExcludeList, Sort, InitRS, LogQuery, Retval, Query";
            
            return $columns;
        }
        
        protected function createSqlValuesQuery() {
            $values = parent::createSqlValuesQuery() . ", " . quote($this->RSCount) .
                    ", " . quote($this->TimeSeekRead) .  ", " . quote($this->PageCache) .  ", " . quote($this->Parse) . 
                    ", " . quote($this->Typee) .  ", " . quote($this->Transform) . ", " . quote($this->Validate) .
                    ", " . quote($this->EvalOrder) .  ", " . quote($this->Evaluate) . ", " . quote($this->ExcludeList) .
                    ", " . quote($this->Sort) .  ", " . quote($this->InitRS) . ", " . quote($this->LogQuery) .
                    ", " . quote($this->Retval) .  ", " . quote($this->Query);
            return $values;
        }
        
    }
    
    
    class GetDocumentResSpace extends FunctionBase
    {
        public $DocCount;
        public $TotalSize;
        
        public function parseFromLine($lineArray) {
            parent::parseFromLine($lineArray);
            $this->DocCount = $lineArray[11];
            $this->TotalSize = $lineArray[12];
        }
        
        
        protected function createSqlColumnsQuery() {
            $columns = parent::createSqlColumnsQuery() . ", DocCount , TotalSize";
            
            return $columns;
        }
        
        protected function createSqlValuesQuery() {
            $values = parent::createSqlValuesQuery() . ", " . quote($this->DocCount) .
                    ", " . quote($this->TotalSize);
            
            return $values;
        }

        public function prettyPrint() 
        {
            $prettyPrint = parent::prettyPrint().
                   " ||| DocCount: " . $this->DocCount; 
            return $prettyPrint;
        }
    }

    class GetDocument extends FunctionBase
    {
        public $DocSize;
        
        public function parseFromLine($lineArray) {
            parent::parseFromLine($lineArray);
            $this->DocSize = $lineArray[9];
        }
        
        public function prettyPrint() {
            $prettyPrint = parent::prettyPrint().
                    " ||| DocSize: " . $this->DocSize;
            return $prettyPrint;
        }
        
        protected function createSqlColumnsQuery() {
            $columns = parent::createSqlColumnsQuery() . ", DocSize";
            
            return $columns;
        }
        
        protected function createSqlValuesQuery() {
            $values = parent::createSqlValuesQuery() . ", " . quote($this->DocSize);
            
            return $values;
        }
        
    }
    
        class UpdateDocuments extends FunctionBase
    {
    	public $DocCount;
    	public $TotalSize;
		public $SyncExtractCount;	
		public $SyncExtractTime;	
	   	public $ProcessSyncIndexCount;		
		public $ProcessSyncIndexTime;	
		public $UpDocsInternalCount;
		public $UpDocsInternalTime ;	
		public $SyncCPCount ;
		public $SyncCPTime ;	
		public $RemSummariesCount;
		public $RemSummariesTime;
		public $CheckUniqueCount;	
		public $CheckUniqueTime ;	
		public $UpdateRepoCount;	
		public $UpdateRepoTime ;	
		public $UpdateStoreCount ;	
		public $UpdateStoreTime;	
		public $GetBPlusCount ;	
		public $GetBPlusTime;	
		public $AddBPlusCount;	
		public $AddBPlusTime ;	
		public $RemStoreCount;
		public $RemStoreTime ;
		public $UnusedMutexCount ;
		public $UnusedMutexTime ;	
		public $PageCacheMutexCount  ;
		public $PageCacheMutexTime ;
		public $SeekReadCount ;	
		public $SeekReadTime  ;
		public $SchedulerMutexCount  ;		
		public $SchedulerMutexTime  ;
		public $SchedulerTotalCount  ;	
		public $SchedulerTotalTime	;
		public $DocNames ;
		
		public function parseFromLine($lineArray) {
			parent::parseFromLine($lineArray);
			$this->DocCount = $lineArray[8];
			$this->TotalSize = $lineArray [9]; 
			$this->SyncExtractCount = $lineArray [10];	
			$this->SyncExtractTime = $lineArray [11];	 	
			$this->ProcessSyncIndexCount = $lineArray [12];			
			$this->ProcessSyncIndexTime = $lineArray [13];		
			$this->UpDocsInternalCount 	= $lineArray [14];	
			$this->UpDocsInternalTime = $lineArray [15];		
			$this->SyncCPCount 	= $lineArray [16];	
			$this->SyncCPTime 	= $lineArray [17];	
			$this->RemSummariesCount = $lineArray [18];		
			$this->RemSummariesTime = $lineArray [19];		
			$this->CheckUniqueCount = $lineArray [20];		
			$this->CheckUniqueTime 	= $lineArray [21];	
			$this->UpdateRepoCount 	= $lineArray [22];	
			$this->UpdateRepoTime 	= $lineArray [23];	
			$this->UpdateStoreCount  = $lineArray [24];		
			$this->UpdateStoreTime = $lineArray [25];		
			$this->GetBPlusCount  	= $lineArray [26];	
			$this->GetBPlusTime 	= $lineArray [27];	
			$this->AddBPlusCount 	= $lineArray [28];	
			$this->AddBPlusTime 	= $lineArray [29];	
			$this->RemStoreCount	= $lineArray [30];	
			$this->RemStoreTime 	= $lineArray [31];	
			$this->UnusedMutexCount = $lineArray [32];		
			$this->UnusedMutexTime 	= $lineArray [33];	
			$this->PageCacheMutexCount = $lineArray [34];	 	
			$this->PageCacheMutexTime  	= $lineArray [35];	
			$this->SeekReadCount 	= $lineArray [36];	
			$this->SeekReadTime   	= $lineArray [37];	
			$this->SchedulerMutexCount  = $lineArray [38];			
			$this->SchedulerMutexTime  = $lineArray [39];	
			$this->SchedulerTotalCount 	= $lineArray [40];	
			$this->SchedulerTotalTime	= $lineArray [41];	
			$this->DocNames = $lineArray [42];	

		}
		public function prettyPrint() {
           $prettyPrint = parent::prettyPrint().
                   " ||| DocCount: " . $this->DocCount.
                   " ||| DocNames: " . $this->DocNames;
           
           return $prettyPrint;
        }
         protected function createSqlColumnsQuery() {
            $columns = parent::createSqlColumnsQuery() . ", DocCount ,TotalSize ,SyncExtractCount , " .
                     "SyncExtractTime, ProcessSyncIndexCount, ProcessSyncIndexTime,  UpDocsInternalCount, UpDocsInternalTime, SyncCPCount, SyncCPTime, RemSummariesCount, RemSummariesTime, CheckUniqueCount, CheckUniqueTime, UpdateRepoCount, UpdateRepoTime, ".
                     "UpdateStoreCount, UpdateStoreTime, GetBPlusCount,	GetBPlusTime, AddBPlusCount, AddBPlusTime,	RemStoreCount, RemStoreTime, UnusedMutexCount,	UnusedMutexTime, PageCacheMutexCount, PageCacheMutexTime, SeekReadCount, SeekReadTime, ".
                     "SchedulerMutexCount,	SchedulerMutexTime, SchedulerTotalCount, SchedulerTotalTime, DocNames";
            
            return $columns;
        }
        
        
            protected function createSqlValuesQuery() {
            $values = parent::createSqlValuesQuery() . ", " . quote($this->DocCount) .
                    ", " . quote($this->TotalSize) .  ", " . quote($this->SyncExtractCount) .  ", " . quote($this->SyncExtractTime) . 
                    ", " . quote($this-> ProcessSyncIndexCount) .  ", " . quote($this->ProcessSyncIndexTime) . ", " . quote($this->UpDocsInternalCount) .
                    ", " . quote($this->UpDocsInternalTime) .  ", " . quote($this->SyncCPCount) . ", " . quote($this->SyncCPTime) .
                    ", " . quote($this->RemSummariesCount) .  ", " . quote($this->RemSummariesTime) . ", " . quote($this->CheckUniqueCount) . "," . quote($this->CheckUniqueTime) .
                    ", " . quote($this->UpdateRepoCount) .  ", " . quote($this->UpdateRepoTime).  ", " . quote($this->UpdateStoreCount).  ", " . quote($this->UpdateStoreTime)
                    .  ", " . quote($this->GetBPlusCount).  ", " . quote($this->GetBPlusTime).  ", " . quote($this->AddBPlusCount).  ", " . quote($this->AddBPlusTime)
                    .  ", " . quote($this->RemStoreCount).  ", " . quote($this->RemStoreTime).  ", " . quote($this->UnusedMutexCount).  ", " . quote($this->UnusedMutexTime)
                    .  ", " . quote($this->PageCacheMutexCount).  ", " . quote($this->PageCacheMutexTime).  ", " . quote($this->SeekReadCount).  ", " . quote($this->SeekReadTime)
                    .  ", " . quote($this->SchedulerMutexCount).  ", " . quote($this->SchedulerMutexTime).  ", " . quote($this->SchedulerTotalCount).  ", " . quote($this->SchedulerTotalTime)
                    .  ", " . quote($this->DocNames);
            return $values;
        }
    
	
    }
    
    class SetDocuments extends FunctionBase
    {
        public $DocCount;  
		public $TotalSize; 
		public $SyncExtractCount;  	
		public $SyncExtractTime;
		public $SyncIdxAddCount;	
		public $SyncIdxAddTime;	
		public $SyncIdxRemCount;	
		public $SyncIdxRemTime;	
		public $ProcessSyncIdxCount;	
		public $ProcessSyncIdxTime;
		public $ProcessSyncPropCount;
		public $ProcessSyncPropTime;
		public $AddToExclListCount;
		public $AddToExclListTime;	
		public $DocNames;
        
        public function parseFromLine($lineArray) {
            parent::parseFromLine($lineArray);
            $this->DocCount = $lineArray[8];
			$this->TotalSize = $lineArray[9];
			$this->SyncExtractCount = $lineArray[10];
			$this->SyncExtractTime = $lineArray[11];
			$this->SyncIdxAddCount = $lineArray[12];	
			$this->SyncIdxAddTime = $lineArray[13];
			$this->SyncIdxRemCount = $lineArray[14];	
			$this->SyncIdxRemTime = $lineArray[15];
			$this->ProcessSyncIdxCount = $lineArray[16];	
			$this->ProcessSyncIdxTime = $lineArray[17];
			$this->ProcessSyncPropCount	= $lineArray[18];
			$this->ProcessSyncPropTime	= $lineArray[19];
			$this->AddToExclListCount  = $lineArray[20];
			$this->AddToExclListTime  = $lineArray[21];
			$this->DocNames = $lineArray[22];
        }
        
        public function prettyPrint() {
            $prettyPrint = parent::prettyPrint().
                    " ||| DocCount: " . $this->DocCount.
                     " ||| DocNames: " . $this->DocNames;
           
            return $prettyPrint;
        }
        protected function createSqlColumnsQuery() {
            $columns = parent::createSqlColumnsQuery() . ", DocCount ,TotalSize ,SyncExtractCount , " .
                     "SyncExtractTime, SyncIdxAddCount, SyncIdxAddTime,  SyncIdxRemCount, SyncIdxRemTime, ProcessSyncIdxCount, ProcessSyncIdxTime, ProcessSyncPropCount,ProcessSyncPropTime, AddToExclListCount, AddToExclListTime, DocNames";
            
            return $columns;
        }
         protected function createSqlValuesQuery() {
            $values = parent::createSqlValuesQuery() . ", " . quote($this->DocCount) .
                    ", " . quote($this->TotalSize) .  ", " . quote($this->SyncExtractCount) .  ", " . quote($this->SyncExtractTime) . 
                    ", " . quote($this-> SyncIdxAddCount) .  ", " . quote($this->SyncIdxAddTime) . ", " . quote($this->SyncIdxRemCount) .
                    ", " . quote($this->SyncIdxRemTime) .  ", " . quote($this->ProcessSyncIdxCount) . ", " . quote($this->ProcessSyncIdxTime) .
                    ", " . quote($this->ProcessSyncPropCount) .  ", " . quote($this->ProcessSyncPropTime) . ", " . quote($this->AddToExclListCount) .
                    ", " . quote($this->AddToExclListTime).", " . quote($this->DocNames);
            return $values;
        }
        
    }
    
    
?>
