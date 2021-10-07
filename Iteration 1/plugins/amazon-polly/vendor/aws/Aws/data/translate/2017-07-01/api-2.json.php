<?php
// This file was auto-generated from sdk-root/src/data/translate/2017-07-01/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2017-07-01', 'endpointPrefix' => 'translate', 'jsonVersion' => '1.1', 'protocol' => 'json', 'serviceFullName' => 'Amazon Translate', 'serviceId' => 'Translate', 'signatureVersion' => 'v4', 'signingName' => 'translate', 'targetPrefix' => 'AWSShineFrontendService_20170701', 'uid' => 'translate-2017-07-01', ], 'operations' => [ 'TranslateText' => [ 'name' => 'TranslateText', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'TranslateTextRequest', ], 'output' => [ 'shape' => 'TranslateTextResponse', ], 'errors' => [ [ 'shape' => 'InvalidRequestException', ], [ 'shape' => 'TextSizeLimitExceededException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'UnsupportedLanguagePairException', ], [ 'shape' => 'DetectedLanguageLowConfidenceException', ], [ 'shape' => 'InternalServerException', ], [ 'shape' => 'ServiceUnavailableException', ], ], ], ], 'shapes' => [ 'BoundedLengthString' => [ 'type' => 'string', 'max' => 5000, 'min' => 1, ], 'DetectedLanguageLowConfidenceException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], 'DetectedLanguageCode' => [ 'shape' => 'LanguageCodeString', ], ], 'exception' => true, ], 'InternalServerException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, 'fault' => true, ], 'InvalidRequestException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, ], 'LanguageCodeString' => [ 'type' => 'string', 'max' => 5, 'min' => 2, ], 'ServiceUnavailableException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, ], 'String' => [ 'type' => 'string', 'min' => 1, ], 'TextSizeLimitExceededException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, ], 'TooManyRequestsException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, ], 'TranslateTextRequest' => [ 'type' => 'structure', 'required' => [ 'Text', 'SourceLanguageCode', 'TargetLanguageCode', ], 'members' => [ 'Text' => [ 'shape' => 'BoundedLengthString', ], 'SourceLanguageCode' => [ 'shape' => 'LanguageCodeString', ], 'TargetLanguageCode' => [ 'shape' => 'LanguageCodeString', ], ], ], 'TranslateTextResponse' => [ 'type' => 'structure', 'required' => [ 'TranslatedText', 'SourceLanguageCode', 'TargetLanguageCode', ], 'members' => [ 'TranslatedText' => [ 'shape' => 'String', ], 'SourceLanguageCode' => [ 'shape' => 'LanguageCodeString', ], 'TargetLanguageCode' => [ 'shape' => 'LanguageCodeString', ], ], ], 'UnsupportedLanguagePairException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'String', ], ], 'exception' => true, ], ],];
