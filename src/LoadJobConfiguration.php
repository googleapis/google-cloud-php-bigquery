<?php

/**
 * Copyright 2017 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\BigQuery;

use Psr\Http\Message\StreamInterface;

/**
 * Represents a configuration for a load job. For more information on the
 * available settings please see the
 * [Jobs configuration API documentation](https://cloud.google.com/bigquery/docs/reference/rest/v2/Job).
 *
 * Example:
 * ```
 * use Google\Cloud\BigQuery\BigQueryClient;
 *
 * $bigQuery = new BigQueryClient();
 * $table = $bigQuery->dataset('my_dataset')
 *     ->table('my_table');
 * $loadJobConfig = $table->load(fopen('/path/to/my/data.csv', 'r'));
 * ```
 */
class LoadJobConfiguration implements JobConfigurationInterface
{
    use JobConfigurationTrait;

    /**
     * @param string $projectId The project's ID.
     * @param array $config A set of configuration options for a job.
     * @param string|null $location The geographic location in which the job is
     *        executed.
     */
    public function __construct($projectId, array $config, $location)
    {
        $this->jobConfigurationProperties($projectId, $config, $location);
    }

    /**
     * Sets whether to accept rows that are missing trailing optional columns.
     * The missing values are treated as nulls. If false, records with missing
     * trailing columns are treated as bad records, and if there are too many
     * bad records, an invalid error is returned in the job result. Only
     * applicable to CSV, ignored for other formats.
     *
     * Example:
     * ```
     * $loadJobConfig->allowJaggedRows(true);
     * ```
     *
     * @param bool $allowJaggedRows Whether or not to allow jagged rows.
     *        **Defaults to** `false`.
     * @return LoadJobConfiguration
     */
    public function allowJaggedRows($allowJaggedRows)
    {
        $this->config['configuration']['load']['allowJaggedRows'] = $allowJaggedRows;

        return $this;
    }

    /**
     * Sets whether quoted data sections that contain newline characters in a
     * CSV file are allowed.
     *
     * Example:
     * ```
     * $loadJobConfig->allowQuotedNewlines(true);
     * ```
     *
     * @param bool $allowQuotedNewlines Whether or not to allow quoted new
     *        lines. **Defaults to** `false`.
     * @return LoadJobConfiguration
     */
    public function allowQuotedNewlines($allowQuotedNewlines)
    {
        $this->config['configuration']['load']['allowQuotedNewlines'] = $allowQuotedNewlines;

        return $this;
    }

    /**
     * Sets whether we should automatically infer the options and schema for CSV
     * and JSON sources.
     *
     * Example:
     * ```
     * $loadJobConfig->autodetect(true);
     * ```
     *
     * @param bool $autodetect Whether or not to autodetect options and schema.
     * @return LoadJobConfiguration
     */
    public function autodetect($autodetect)
    {
        $this->config['configuration']['load']['autodetect'] = $autodetect;

        return $this;
    }

    /**
     * Set the clustering specification for the table.
     *
     * Refer to BigQuery documentation for a guide to clustered tables and
     * constraints imposed by the service.
     *
     * Example:
     * ```
     * $loadJobConfig->clustering([
     *     'fields' => [
     *         'col1',
     *         'col2'
     *     ]
     * ]);
     * ```
     *
     * @see https://cloud.google.com/bigquery/docs/clustered-tables Introduction to Clustered Tables
     *
     * @param array $clustering Clustering specification for the table.
     * @return LoadJobConfiguration
     */
    public function clustering(array $clustering)
    {
        $this->config['configuration']['load']['clustering'] = $clustering;

        return $this;
    }

    /**
     * Set whether the job is allowed to create new tables. Creation, truncation
     * and append actions occur as one atomic update upon job completion.
     *
     * Example:
     * ```
     * $loadJobConfig->createDisposition('CREATE_NEVER');
     * ```
     *
     * @param string $createDisposition The create disposition. Acceptable
     *        values include `"CREATED_IF_NEEDED"`, `"CREATE_NEVER"`. **Defaults
     *        to** `"CREATE_IF_NEEDED"`.
     * @return LoadJobConfiguration
     */
    public function createDisposition($createDisposition)
    {
        $this->config['configuration']['load']['createDisposition'] = $createDisposition;

        return $this;
    }

    /**
     * Sets the custom encryption configuration (e.g., Cloud KMS keys).
     *
     * Example:
     * ```
     * $loadJobConfig->destinationEncryptionConfiguration([
     *     'kmsKeyName' => 'my_key'
     * ]);
     * ```
     *
     * @param array $configuration Custom encryption configuration.
     * @return LoadJobConfiguration
     */
    public function destinationEncryptionConfiguration(array $configuration)
    {
        $this->config['configuration']['load']['destinationEncryptionConfiguration'] = $configuration;

        return $this;
    }

    /**
     * The data to be loaded into the table.
     *
     * Example:
     * ```
     * $loadJobConfig->data(fopen('/path/to/my/data.csv', 'r'));
     * ```
     *
     * @param string|resource|StreamInterface $data The data to be loaded into
     *        the table.
     * @return LoadJobConfiguration
     */
    public function data($data)
    {
        $this->config['data'] = $data;

        return $this;
    }

    /**
     * Sets the destination table to load the data into.
     *
     * Example:
     * ```
     * $table = $bigQuery->dataset('my_dataset')
     *     ->table('my_table');
     * $loadJobConfig->destinationTable($table);
     * ```
     *
     * @param Table $destinationTable The destination table.
     * @return LoadJobConfiguration
     */
    public function destinationTable(Table $destinationTable)
    {
        $this->config['configuration']['load']['destinationTable'] = $destinationTable->identity();

        return $this;
    }

    /**
     * Sets the character encoding of the data. BigQuery decodes the data after
     * the raw, binary data has been split using the values of the quote and
     * fieldDelimiter properties.
     *
     * Example:
     * ```
     * $loadJobConfig->encoding('UTF-8');
     * ```
     *
     * @param string $encoding The encoding type. Acceptable values include
     *        `"UTF-8"`, `"ISO-8859-1"`. **Defaults to** `"UTF-8"`.
     * @return LoadJobConfiguration
     */
    public function encoding($encoding)
    {
        $this->config['configuration']['load']['encoding'] = $encoding;

        return $this;
    }

    /**
     * Sets the separator for fields in a CSV file. The separator can be any
     * ISO-8859-1 single-byte character. To use a character in the range
     * 128-255, you must encode the character as UTF8. BigQuery converts the
     * string to ISO-8859-1 encoding, and then uses the first byte of the
     * encoded string to split the data in its raw, binary state. BigQuery also
     * supports the escape sequence "\t" to specify a tab separator.
     *
     * Example:
     * ```
     * $loadJobConfig->fieldDelimiter('\t');
     * ```
     *
     * @param string $fieldDelimiter The field delimiter. **Defaults to** `","`.
     * @return LoadJobConfiguration
     */
    public function fieldDelimiter($fieldDelimiter)
    {
        $this->config['configuration']['load']['fieldDelimiter'] = $fieldDelimiter;

        return $this;
    }

    /**
     * Sets whether values that are not represented in the table schema should
     * be allowed. If true, the extra values are ignored. If false, records with
     * extra columns are treated as bad records, and if there are too many bad
     * records, an invalid error is returned in the job result.
     *
     * The sourceFormat property determines what BigQuery treats as an extra
     * value:
     *
     * - CSV: Trailing columns.
     * - JSON: Named values that don't match any column names.
     *
     * Example:
     * ```
     * $loadJobConfig->ignoreUnknownValues(true);
     * ```
     *
     * @param bool $ignoreUnknownValues Whether or not to ignore unknown values.
     *        **Defaults to** `false`.
     * @return LoadJobConfiguration
     */
    public function ignoreUnknownValues($ignoreUnknownValues)
    {
        $this->config['configuration']['load']['ignoreUnknownValues'] = $ignoreUnknownValues;

        return $this;
    }

    /**
     * Sets the maximum number of bad records that BigQuery can ignore when
     * running the job. If the number of bad records exceeds this value, an
     * invalid error is returned in the job result.
     *
     * Example:
     * ```
     * $loadJobConfig->maxBadRecords(10);
     * ```
     *
     * @param int $maxBadRecords The maximum number of bad records.
     *        **Defaults to** `0` (requires all records to be valid).
     * @return LoadJobConfiguration
     */
    public function maxBadRecords($maxBadRecords)
    {
        $this->config['configuration']['load']['maxBadRecords'] = $maxBadRecords;

        return $this;
    }

    /**
     * Sets a string that represents a null value in a CSV file. For example,
     * if you specify "\N", BigQuery interprets "\N" as a null value when
     * loading a CSV file. The default value is the empty string. If you set
     * this property to a custom value, BigQuery throws an error if an empty
     * string is present for all data types except for STRING and BYTE. For
     * STRING and BYTE columns, BigQuery interprets the empty string as an
     * empty value.
     *
     * Example:
     * ```
     * $loadJobConfig->nullMarker('\N');
     * ```
     *
     * @param string $nullMarker The null marker.
     * @return LoadJobConfiguration
     */
    public function nullMarker($nullMarker)
    {
        $this->config['configuration']['load']['nullMarker'] = $nullMarker;

        return $this;
    }

    /**
     * Sets a list of projection fields. If sourceFormat is set to
     * "DATASTORE_BACKUP", indicates which entity properties to load into
     * BigQuery from a Cloud Datastore backup. Property names are case sensitive
     * and must be top-level properties. If no properties are specified,
     * BigQuery loads all properties. If any named property isn't found in the
     * Cloud Datastore backup, an invalid error is returned in the job result.
     *
     * Example:
     * ```
     * $loadJobConfig->projectionFields([
     *     'field_name'
     * ]);
     * ```
     *
     * @param array $projectionFields The projection fields.
     * @return LoadJobConfiguration
     */
    public function projectionFields(array $projectionFields)
    {
        $this->config['configuration']['load']['projectionFields'] = $projectionFields;

        return $this;
    }

    /**
     * Sets the value that is used to quote data sections in a CSV file.
     * BigQuery converts the string to ISO-8859-1 encoding, and then uses the
     * first byte of the encoded string to split the data in its raw, binary
     * state. If your data does not contain quoted sections, set the property
     * value to an empty string. If your data contains quoted newline
     * characters, you must also set the allowQuotedNewlines property to true.
     *
     * Example:
     * ```
     * $loadJobConfig->quote('"');
     * ```
     *
     * @param string $quote The quote value. **Defaults to** `"""`
     *        (double quotes).
     * @return LoadJobConfiguration
     */
    public function quote($quote)
    {
        $this->config['configuration']['load']['quote'] = $quote;

        return $this;
    }

    /**
     * Sets the schema for the destination table. The schema can be omitted if
     * the destination table already exists, or if you're loading data from
     * Google Cloud Datastore.
     *
     * Example:
     * ```
     * $loadJobConfig->schema([
     *     'fields' => [
     *         [
     *             'name' => 'col1',
     *             'type' => 'STRING',
     *         ],
     *         [
     *             'name' => 'col2',
     *             'type' => 'BOOL',
     *         ]
     *     ]
     * ]);
     * ```
     *
     * @param array $schema The table schema.
     * @return LoadJobConfiguration
     */
    public function schema(array $schema)
    {
        $this->config['configuration']['load']['schema'] = $schema;

        return $this;
    }

    /**
     * Sets options to allow the schema of the destination table to be updated
     * as a side effect of the query job. Schema update options are supported
     * in two cases: when writeDisposition is `"WRITE_APPEND"`; when
     * writeDisposition is `"WRITE_TRUNCATE"` and the destination table is a
     * partition of a table, specified by partition decorators. For normal
     * tables, `"WRITE_TRUNCATE"` will always overwrite the schema.
     *
     * Example:
     * ```
     * $loadJobConfig->schemaUpdateOptions([
     *     'ALLOW_FIELD_ADDITION'
     * ]);
     * ```
     *
     * @param array $schemaUpdateOptions Schema update options. Acceptable
     *        values include `"ALLOW_FIELD_ADDITION"` (allow adding a nullable
     *        field to the schema),  `"ALLOW_FIELD_RELAXATION"` (allow relaxing
     *        a required field in the original schema to nullable).
     * @return LoadJobConfiguration
     */
    public function schemaUpdateOptions(array $schemaUpdateOptions)
    {
        $this->config['configuration']['load']['schemaUpdateOptions'] = $schemaUpdateOptions;

        return $this;
    }

    /**
     * Sets the number of rows at the top of a CSV file that BigQuery will skip
     * when loading the data. This property is useful if you have header rows in
     * the file that should be skipped.
     *
     * Example:
     * ```
     * $loadJobConfig->skipLeadingRows(10);
     * ```
     *
     * @param int $skipLeadingRows The number of rows to skip.
     *        **Defaults to** `0`.
     * @return LoadJobConfiguration
     */
    public function skipLeadingRows($skipLeadingRows)
    {
        $this->config['configuration']['load']['skipLeadingRows'] = $skipLeadingRows;

        return $this;
    }

    /**
     * Sets the format of the data files.
     *
     * Example:
     * ```
     * $loadJobConfig->sourceFormat('NEWLINE_DELIMITED_JSON');
     * ```
     *
     * @param string $sourceFormat The source format. Acceptable values include
     *        `"CSV"`, `"DATASTORE_BACKUP"`, `"NEWLINE_DELIMITED_JSON"`,
     *        `"AVRO"`, `"PARQUET"`, `"ORC"`. **Defaults to** `"CSV"`.
     * @return LoadJobConfiguration
     */
    public function sourceFormat($sourceFormat)
    {
        $this->config['configuration']['load']['sourceFormat'] = $sourceFormat;

        return $this;
    }

    /**
     * Sets the fully-qualified URIs that point to your data in Google Cloud.
     *
     * - For Google Cloud Storage URIs: Each URI can contain one '*' wildcard
     *   character and it must come after the 'bucket' name.
     * - For Google Cloud Bigtable URIs: Exactly one URI can be specified and it
     *   has be a fully specified and valid HTTPS URL for a Google Cloud Bigtable
     *   table.
     * - For Google Cloud Datastore backups: Exactly one URI can be specified.
     *   Also, the '*' wildcard character is not allowed.
     *
     * Example:
     * ```
     * $loadJobConfig->sourceUris([
     *     'gs://my_bucket/source.csv'
     * ]);
     * ```
     *
     * @param array $sourceUris The source URIs.
     * @return LoadJobConfiguration
     */
    public function sourceUris(array $sourceUris)
    {
        $this->config['configuration']['load']['sourceUris'] = $sourceUris;

        return $this;
    }

    /**
     * Sets time-based partitioning for the destination table.
     *
     * Only one of timePartitioning and rangePartitioning should be specified.
     *
     * Example:
     * ```
     * $loadJobConfig->timePartitioning([
     *     'type' => 'DAY'
     * ]);
     * ```
     *
     * @param array $timePartitioning Time-based partitioning configuration.
     * @return LoadJobConfiguration
     */
    public function timePartitioning(array $timePartitioning)
    {
        $this->config['configuration']['load']['timePartitioning'] = $timePartitioning;

        return $this;
    }

    /**
     * Sets range partitioning specification for the destination table.
     *
     * Only one of timePartitioning and rangePartitioning should be specified.
     *
     * Example:
     * ```
     * $loadJobConfig->rangePartitioning([
     *     'field' => 'myInt',
     *     'range' => [
     *         'start' => '0',
     *         'end' => '1000',
     *         'interval' => '100'
     *     ]
     * ]);
     * ```
     *
     * @param array $rangePartitioning
     * @return LoadJobConfiguration
     */
    public function rangePartitioning(array $rangePartitioning)
    {
        $this->config['configuration']['load']['rangePartitioning'] = $rangePartitioning;

        return $this;
    }

    /**
     * Sets the action that occurs if the destination table already exists. Each
     * action is atomic and only occurs if BigQuery is able to complete the job
     * successfully. Creation, truncation and append actions occur as one atomic
     * update upon job completion.
     *
     * Example:
     * ```
     * $loadJobConfig->writeDisposition('WRITE_TRUNCATE');
     * ```
     *
     * @param string $writeDisposition The write disposition. Acceptable values
     *        include `"WRITE_TRUNCATE"`, `"WRITE_APPEND"`, `"WRITE_EMPTY"`.
     *        **Defaults to** `"WRITE_APPEND"`.
     * @return LoadJobConfiguration
     */
    public function writeDisposition($writeDisposition)
    {
        $this->config['configuration']['load']['writeDisposition'] = $writeDisposition;

        return $this;
    }

    /**
     * Sets whether to use logical types when loading from AVRO format.
     *
     * If sourceFormat is set to "AVRO", indicates whether to enable
     * interpreting logical types into their corresponding types (ie.
     * TIMESTAMP), instead of only using their raw types (ie. INTEGER).
     *
     * Example:
     * ```
     * $loadJobConfig->useAvroLogicalTypes(true);
     * ```
     *
     * @param bool $useAvroLogicalTypes
     * @return LoadJobConfiguration
     */
    public function useAvroLogicalTypes($useAvroLogicalTypes)
    {
        $this->config['configuration']['load']['useAvroLogicalTypes'] = $useAvroLogicalTypes;

        return $this;
    }

    /**
     * Set hive partitioning options.
     *
     * When set, configures hive partitioning support. Not all storage formats
     * support hive partitioning -- requesting hive partitioning on an
     * unsupported format will lead to an error, as will providing an invalid
     * specification.
     *
     * Example:
     * ```
     * $loadJobConfig->hivePartitioningOptions([
     *     'mode' => 'AUTO',
     *     'sourceUriPrefix' => 'gs://bucket/path_to_table',
     *     'requirePartitionFilter' => false,
     * ]);
     * ```
     *
     * @codingStandardsIgnoreStart
     * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/tables#HivePartitioningOptions HivePartitoningOptions
     * @codingStandardsIgnoreEnd
     *
     * @param array $hivePartitioningOptions
     * @return LoadJobConfiguration
     */
    public function hivePartitioningOptions(array $hivePartitioningOptions)
    {
        $this->config['configuration']['load']['hivePartitioningOptions'] = $hivePartitioningOptions;

        return $this;
    }

    /**
     * Decide whether to create a new session with a random id.
     * The created session id is returned as part of the SessionInfo
     * within the query statistics.
     *
     * Example:
     * ```
     * $loadJobConfig->createSession(true);
     * ```
     *
     * @param bool $createSession
     * @return LoadJobConfiguration
     */
    public function createSession(bool $createSession)
    {
        $this->config['configuration']['load']['createSession'] = $createSession;

        return $this;
    }

    /**
     * Sets connection properties for the load job.
     *
     * Example:
     * ```
     * $loadJobConfig->connectionProperties([
     *     'key' => 'session_id',
     *     'value' => 'sessionId'
     * ]);
     * ```
     *
     * @param array $connectionProperties
     * @return LoadJobConfiguration
     */
    public function connectionProperties(array $connectionProperties)
    {
        $this->config['configuration']['load']['connectionProperties'] =
            $connectionProperties;

        return $this;
    }

    /**
     * Sets the reference for external table schema.
     * It is enabled for AVRO, PARQUET and ORC format.
     *
     * Example:
     * ```
     * $loadJobConfig->referenceFileSchemaUri('gs://bucket/source.parquet');
     * ```
     *
     * @param string $referenceFileSchemaUri
     * @return LoadJobConfiguration
     */
    public function referenceFileSchemaUri(string $referenceFileSchemaUri)
    {
        $this->config['configuration']['load']['referenceFileSchemaUri'] = $referenceFileSchemaUri;

        return $this;
    }

    /**
     * Character map supported for column names in CSV/Parquet loads.
     * Defaults to STRICT and can be overridden by Project Config Service. Using
     * this option with unsupporting load formats will result in an error.
     *
     * Example:
     * ```
     * $loadJobConfig->columnNameCharacterMap('V2');
     * ```
     *
     * @param string $columnNameCharacterMap The column name character map.
     *        Acceptable values include "COLUMN_NAME_CHARACTER_MAP_UNSPECIFIED",
     *        "STRICT", "V1", "V2".
     * @return LoadJobConfiguration
     */
    public function columnNameCharacterMap(string $columnNameCharacterMap): self
    {
        $this->config['configuration']['load']['columnNameCharacterMap'] = $columnNameCharacterMap;

        return $this;
    }

    /**
     * [Experimental] Configures the load job to copy files directly to the
     * destination BigLake managed table, bypassing file content reading and
     * rewriting. Copying files only is supported when all the following are
     * true:
     * * `source_uris` are located in the same Cloud Storage location as the
     *   destination table's `storage_uri` location.
     * * `source_format` is `PARQUET`.
     * * `destination_table` is an existing BigLake managed table. The table's
     *   schema does not have flexible column names. The table's columns do not
     *   have type parameters other than precision and scale.
     * * No options other than the above are specified.
     *
     * Example:
     * ```
     * $loadJobConfig->copyFilesOnly(true);
     * ```
     *
     * @param bool $copyFilesOnly Whether to copy files only.
     * @return LoadJobConfiguration
     */
    public function copyFilesOnly(bool $copyFilesOnly): self
    {
        $this->config['configuration']['load']['copyFilesOnly'] = $copyFilesOnly;

        return $this;
    }

    /**
     * Date format used for parsing DATE values.
     *
     * Example:
     * ```
     * $loadJobConfig->dateFormat('%Y-%m-%d');
     * ```
     *
     * @param string $dateFormat The date format string.
     * @return LoadJobConfiguration
     */
    public function dateFormat(string $dateFormat): self
    {
        $this->config['configuration']['load']['dateFormat'] = $dateFormat;

        return $this;
    }

    /**
     * Date format used for parsing DATETIME values.
     *
     * Example:
     * ```
     * $loadJobConfig->datetimeFormat('%Y-%m-%d %H:%M:%S');
     * ```
     *
     * @param string $datetimeFormat The datetime format string.
     * @return LoadJobConfiguration
     */
    public function datetimeFormat(string $datetimeFormat): self
    {
        $this->config['configuration']['load']['datetimeFormat'] = $datetimeFormat;

        return $this;
    }

    /**
     * Defines the list of possible SQL data types to which the source decimal
     * values are converted. This list and the precision and the scale parameters
     * of the decimal field determine the target type. In the order of NUMERIC,
     * BIGNUMERIC, and STRING, a type is picked if it is in the specified list
     * and if it supports the precision and the scale. STRING supports all
     * precision and scale values. If none of the listed types supports the
     * precision and the scale, the type supporting the widest range in the
     * specified list is picked, and if a value exceeds the supported range
     * when reading the data, an error will be thrown. Example: Suppose the value
     * of this field is ["NUMERIC", "BIGNUMERIC"]. If (precision,scale) is:
     * * (38,9) -> NUMERIC;
     * * (39,9) -> BIGNUMERIC (NUMERIC cannot hold 30 integer digits);
     * * (38,10) -> BIGNUMERIC (NUMERIC cannot hold 10 fractional digits);
     * * (76,38) -> BIGNUMERIC;
     * * (77,38) -> BIGNUMERIC (error if value exceeds supported range).
     * This field cannot contain duplicate types. The order of the types in this
     * field is ignored. For example, ["BIGNUMERIC", "NUMERIC"] is the same as
     * ["NUMERIC", "BIGNUMERIC"] and NUMERIC always takes precedence over BIGNUMERIC.
     * Defaults to ["NUMERIC", "STRING"] for ORC and ["NUMERIC"] for the other
     * file formats.
     *
     * Example:
     * ```
     * $loadJobConfig->decimalTargetTypes(['NUMERIC', 'BIGNUMERIC']);
     * ```
     *
     * @param string[] $decimalTargetTypes An array of target decimal types.
     *        Acceptable values include "DECIMAL_TARGET_TYPE_UNSPECIFIED",
     *        "NUMERIC", "BIGNUMERIC", "STRING".
     * @return LoadJobConfiguration
     */
    public function decimalTargetTypes(array $decimalTargetTypes): self
    {
        $this->config['configuration']['load']['decimalTargetTypes'] = $decimalTargetTypes;

        return $this;
    }

    /**
     * [Experimental] Properties with which to create the destination table
     * if it is new.
     *
     * Example:
     * ```
     * $loadJobConfig->destinationTableProperties([
     *     'description' => 'My new table',
     *     'friendlyName' => 'New Table',
     *     'labels' => ['env' => 'dev']
     * ]);
     * ```
     *
     * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/Job#destinationtableproperties
     *      DestinationTableProperties
     *
     * @param array $destinationTableProperties Properties for the destination table.
     * @return LoadJobConfiguration
     */
    public function destinationTableProperties(array $destinationTableProperties): self
    {
        $this->config['configuration']['load']['destinationTableProperties'] = $destinationTableProperties;

        return $this;
    }

    /**
     * Specifies how source URIs are interpreted for constructing the
     * file set to load. By default, source URIs are expanded against the
     * underlying storage. You can also specify manifest files to control how the
     * file set is constructed. This option is only applicable to object storage
     * systems.
     *
     * Example:
     * ```
     * $loadJobConfig->fileSetSpecType('FILE_SET_SPEC_TYPE_NEW_LINE_DELIMITED_MANIFEST');
     * ```
     *
     * @param string $fileSetSpecType The file set specification type.
     *        Acceptable values include "FILE_SET_SPEC_TYPE_FILE_SYSTEM_MATCH",
     *        "FILE_SET_SPEC_TYPE_NEW_LINE_DELIMITED_MANIFEST".
     * @return LoadJobConfiguration
     */
    public function fileSetSpecType(string $fileSetSpecType): self
    {
        $this->config['configuration']['load']['fileSetSpecType'] = $fileSetSpecType;

        return $this;
    }

    /**
     * Load option to be used together with source_format
     * newline-delimited JSON to indicate that a variant of JSON is being loaded.
     * To load newline-delimited GeoJSON, specify GEOJSON (and source_format
     * must be set to NEWLINE_DELIMITED_JSON).
     *
     * Example:
     * ```
     * $loadJobConfig->jsonExtension('GEOJSON');
     * ```
     *
     * @param string $jsonExtension The JSON extension type.
     *        Acceptable values include "JSON_EXTENSION_UNSPECIFIED", "GEOJSON".
     * @return LoadJobConfiguration
     */
    public function jsonExtension(string $jsonExtension): self
    {
        $this->config['configuration']['load']['jsonExtension'] = $jsonExtension;

        return $this;
    }

    /**
     * A list of strings represented as SQL NULL value in a CSV file.
     * null_marker and null_markers can't be set at the same time. If null_marker
     * is set, null_markers has to be not set. If null_markers is set, null_marker
     * has to be not set. If both null_marker and null_markers are set at the same
     * time, a user error would be thrown. Any strings listed in null_markers,
     * including empty string would be interpreted as SQL NULL. This applies to all
     * column types.
     *
     * Example:
     * ```
     * $loadJobConfig->nullMarkers(['\\N', 'NULL']);
     * ```
     *
     * @param string[] $nullMarkers An array of strings to be interpreted as NULL.
     * @return LoadJobConfiguration
     */
    public function nullMarkers(array $nullMarkers): self
    {
        $this->config['configuration']['load']['nullMarkers'] = $nullMarkers;

        return $this;
    }

    /**
     * Additional properties to set if sourceFormat is set to PARQUET.
     *
     * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/tables#ParquetOptions ParquetOptions
     *
     * Example:
     * ```
     * $loadJobConfig->parquetOptions([
     *     'enumAsString' => true
     * ]);
     * ```
     *
     * @param array $parquetOptions Additional Parquet options.
     * @return LoadJobConfiguration
     */
    public function parquetOptions(array $parquetOptions): self
    {
        $this->config['configuration']['load']['parquetOptions'] = $parquetOptions;

        return $this;
    }

    /**
     * When sourceFormat is set to "CSV", this indicates whether the
     * embedded ASCII control characters (the first 32 characters in the
     * ASCII-table, from '\\x00' to '\\x1F') are preserved.
     *
     * Example:
     * ```
     * $loadJobConfig->preserveAsciiControlCharacters(true);
     * ```
     *
     * @param bool $preserveAsciiControlCharacters Whether to preserve ASCII
     *        control characters.
     * @return LoadJobConfiguration
     */
    public function preserveAsciiControlCharacters(bool $preserveAsciiControlCharacters): self
    {
        $this->config['configuration']['load']['preserveAsciiControlCharacters'] = $preserveAsciiControlCharacters;

        return $this;
    }

    /**
     * Controls the strategy used to match loaded columns to the schema.
     * If not set, a sensible default is chosen based on how the schema is
     * provided. If autodetect is used, then columns are matched by name.
     * Otherwise, columns are matched by position. This is done to keep the
     * behavior backward-compatible.
     *
     * Example:
     * ```
     * $loadJobConfig->sourceColumnMatch('NAME');
     * ```
     *
     * @param string $sourceColumnMatch The column match strategy.
     *        Acceptable values include "SOURCE_COLUMN_MATCH_UNSPECIFIED",
     *        "POSITION", "NAME".
     * @return LoadJobConfiguration
     */
    public function sourceColumnMatch(string $sourceColumnMatch): self
    {
        $this->config['configuration']['load']['sourceColumnMatch'] = $sourceColumnMatch;

        return $this;
    }

    /**
     * Date format used for parsing TIME values.
     *
     * Example:
     * ```
     * $loadJobConfig->timeFormat('%H:%M:%S');
     * ```
     *
     * @param string $timeFormat The time format string.
     * @return LoadJobConfiguration
     */
    public function timeFormat(string $timeFormat): self
    {
        $this->config['configuration']['load']['timeFormat'] = $timeFormat;

        return $this;
    }

    /**
     * Default time zone that will apply when parsing timestamp values
     * that have no specific time zone.
     *
     * Example:
     * ```
     * $loadJobConfig->timeZone('America/Los_Angeles');
     * ```
     *
     * @param string $timeZone The default time zone string.
     * @return LoadJobConfiguration
     */
    public function timeZone(string $timeZone): self
    {
        $this->config['configuration']['load']['timeZone'] = $timeZone;

        return $this;
    }

    /**
     * Date format used for parsing TIMESTAMP values.
     *
     * Example:
     * ```
     * $loadJobConfig->timestampFormat('%Y-%m-%d %H:%M:%S%F');
     * ```
     *
     * @param string $timestampFormat The timestamp format string.
     * @return LoadJobConfiguration
     */
    public function timestampFormat(string $timestampFormat): self
    {
        $this->config['configuration']['load']['timestampFormat'] = $timestampFormat;

        return $this;
    }
}
