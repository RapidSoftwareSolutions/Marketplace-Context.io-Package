[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/ContextIO/functions?utm_source=RapidAPIGitHub_ContextIOFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# ContextIO Package
ContextIO is a modern, scalable email API that brings IMAP into the 21st century.Enrich your application with email data: contacts, attachments, receipts, reservations, travel information, and much more.Context.IO can connect to any IMAP enabled email including Gmail, Google Apps, Outlook, Yahoo, AOL, and more.Get near real-time notifications for new and sent messages via webhooks. We offer rich filtering features to ensure you only get the data you need.Context.IO is free to use for most cases, but we offer flexible pricing tiers for companies of any size.
* Domain: [context.com](https://context.io/)
* Credentials: consumerKey, consumerSecret

## How to get credentials: 
1. Register on the [context.com](https://context.io/).
2. After register, in [console](https://console.context.io/), you will see OAuth consumer key and secret.
 
 
## Custom datatypes: 
 |Datatype|Description|Example
 |--------|-----------|----------
 |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
 |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
 |List|Simple array|```["123", "sample"]``` 
 |Select|String with predefined values|```sample```
 |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```
 
## ContextIO.createAccount
You can create an account with no source by only passing in the email param. If you choose this option, you will need to add a source later.

| Field               | Type       | Description
|---------------------|------------|----------
| consumerKey         | credentials| The consumer key from your account.
| consumerSecret      | credentials| The consumer secret from your account.
| email               | String     | The primary email address used to receive emails in this account.
| firstName           | String     | First name of the account holder.
| lastName            | String     | Last name of the account holder.
| syncAllFolders      | Select     | By default, we filter out some folders like ‘Deleted Items’ and 'Drafts’. Set this parameter to `off` to turn off this filtering and show every single folder.Options - only `off`.
| expungeOnDeletedFlag| Select     | By default, we don’t filter out messages flagged as deleted. Set this parameter to `on` to turn on this filtering.Options - only 'on'.
| callbackUrl         | String     | If specified, we’ll make a POST request to this URL when the initial sync is completed. 
| statusCallbackUrl   | String     | If specified, we’ll make a POST request to this URL if the connection status of the source changes. 

## ContextIO.createSources
Use this endpoint to add a source to an existing account. An account id is required to add a source to an existing account.

| Field               | Type       | Description
|---------------------|------------|----------
| consumerKey         | credentials| The consumer key from your account.
| consumerSecret      | credentials| The consumer secret from your account.
| accountId           | String     | Unique id of an account.
| email               | String     | The primary email address used to receive emails in this account.
| username            | String     | The username used to authenticate an IMAP connection. On some servers, this is the same thing as the primary email address.
| server              | String     | Name of IP of the IMAP server, eg. imap.gmail.com.
| useSsl              | Number     | Set to 1 if you want SSL encryption to be used when opening connections to the IMAP server. Any other value will be considered as “do not use SSL”.
| port                | Number     | Port number to connect to on the server. For most servers, SSL is 993, and unencrypted (non-SSL) is 143. 
| type                | Select     | Currently, the only supported type is IMAP.
| originIp            | String     | IP address of the end user requesting the account to be created
| syncAllFolders      | Select     | By default, we filter out some folders like ‘Deleted Items’ and 'Drafts’. Set this parameter to `off` to turn off this filtering and show every single folder.Options - only `off`.
| expungeOnDeletedFlag| Select     | By default, we don’t filter out messages flagged as deleted. Set this parameter to `on` to turn on this filtering.Options - only `on`.
| password            | String     | Password for this source. Required when creating a source and account in a single call. Ignored if any of the provider_* parameters are set below.
| providerRefreshToken| String     | An OAuth2 refresh token obtained from the IMAP account provider to authenticate this email account. Required if using oauth when creating a source and account in a single call.
| providerConsumerKey | String     | The OAuth2 Client ID used to obtain the the refresh token for the above account. Required if using oauth when creating a source and account in a single call. 
| callbackUrl         | String     | If specified, we’ll make a POST request to this URL when the initial sync is completed. 
| statusCallbackUrl   | String     | If specified, we’ll make a POST request to this URL if the connection status of the source changes. 
| rawFileList         | Select     | By default, we filter out files like signature images or those winmail.dat files form the files list. Set this parameter to `off` to turn off this filtering and show every single file attachments.Options - only `off`.

## ContextIO.deleteAccount
If you need to delete an account, you can do so by using this endpoint. This will delete the account and all sources associated with it.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.

## ContextIO.deleteSource
If you wish to only delete a certain source from the account, but not delete the account itself.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| label         | String     | The label property of the source instance. You can use 0 as an alias for the first source of an account.

## ContextIO.connectTokensWizard
Connect tokens are a feature we provide to facilitate account creation.

| Field                     | Type       | Description
|---------------------------|------------|----------
| consumerKey               | credentials| The consumer key from your account.
| consumerSecret            | credentials| The consumer secret from your account.
| callbackUrl               | String     | When the user’s mailbox is connected to your API key, the browser will call this url (GET). This call will have a parameter called contextio_token indicating the connect_token related to this callback. You can then do a get on this connect_token to obtain details about the account and source created through that token and save that account id in your own user data.
| email                     | String     | The email address of the account to be added. If specified, the first step of the connect UI where users are prompted for their email address, first name and last name is skipped.
| firstName                 | String     | First name of the account holder.
| lastName                  | String     | Last name of the account holder.
| sourceCallbackUrl         | String     | If specified, we’ll make a POST request to this URL when the initial sync is completed.
| sourceExpungeOnDeletedFlag| Select     | By default, we don’t filter out messages flagged as deleted. Set this parameter to `on` to turn on this filtering.
| sourceSyncAllFolders      | Select     | By default, we filter out some folders like ‘Deleted Items’ and 'Drafts’. Set this parameter to `off` to turn off this filtering and show every single folder.Options - only `off`.
| sourceSyncFolders         | Select     | By default, we filter out some folders like 'Deleted Items’ and 'Drafts’. Set this parameter to `All`,`Trash` to show the 'Deleted Items’ folder.Options - All,Trash.
| sourceRawFileList         | Select     | By default, we filter out files like signature images or those winmail.dat files form the files list. Set this parameter to `off` to turn off this filtering and show every single file attachments.Options - only `off`.
| statusCallbackUrl         | String     | If specified, we’ll make a POST request to this URL if the connection status of the source changes. 

## ContextIO.getConnectTokenDetails
Getting data about connect token will tell you:If the connect token was used;It not used, you will see when it will expire;If the connect token was used, you will see data about which source was added with the connect token;

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| token         | String     | The unique random token used to add a second source to an existing account.

## ContextIO.getAllConnectTokensForAccount
Use this call to list all connect tokens associated with an account (used or unused).

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.

## ContextIO.getAllConnectTokensForSource
Use this call to list all connect tokens associated with a source (used or unused).

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| label         | String     | The label property of the source instance.

## ContextIO.getAccountDetails
Getting details at the account level will give you:Account creation date;List all sources linked to the account;Number of messages currently in the mailbox;

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.

## ContextIO.getAllSources
If the account has more than once source, this endpoint will list all sources associated with the account. Optional parameters will allow you to filter by source status.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| status        | Select     | Only return sources whose status is of a specific value. Opions - "INVALID_CREDENTIALS","CONNECTION_IMPOSSIBLE", "OK", "TEMP_DISABLED", "DISABLED".                                                                                                                    
| statusOk      | Select     | Set to `false` to get sources that are not working correctly. Set to `true` to get those that are. OPtions - true,false.

## ContextIO.getSourceDetail
The name of a source in Context.IO is called a “label”. The label will be included in the response when you get account or source details and looks something like `email::provider`.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| label         | String     | The label property of the source instance.

## ContextIO.getAllMessages
List all messages in the account (including all sources associated with the account). This call hits our cache of metadata for the account, so this call should be faster than hitting IMAP.

| Field            | Type       | Description
|------------------|------------|----------
| consumerKey      | credentials| The consumer key from your account.
| consumerSecret   | credentials| The consumer secret from your account.
| accountId        | String     | Unique id of an account.
| subject          | String     | Get messages whose subject matches this search string. To use regular expressions instead of simple string matching, make sure the string starts and ends with /.
| emails           | List       | Email address(es) or top level domain of the contact for whom you want the latest messages exchanged with. By “exchanged with contact X” we mean any email received from contact X, sent to contact X or sent by anyone to both contact X and the source owner. This accepts a single address or a comma separated list.
| to               | String     | Email address of a contact messages have been sent to.
| from             | String     | Email address of a contact messages have been received from.
| emailOfContactCC | String     | Email address of a contact CC'ed on the messages.
| emailOfContactBCC| String     | Email address of a contact BCC'ed on the messages.
| folder           | String     | Filter messages by the folder (or Gmail label).This parameter can be the complete folder name with the appropriate hierarchy delimiter for the mail server being queried (eg. Inbox/My folder) or the “symbolic name” of the folder (eg. \Starred). The symbolic name refers to attributes used to refer to special use folders in a language-independent way. See RFC-6154.
| source           | String     | Filter messages by the account source label.
| fileName         | String     | Search for files based on their name. You can filter names using typical shell wildcards such as *, ? and [] or regular expressions by enclosing the search expression in a leading / and trailing /. For example, *.pdf would give you all PDF files while /.jpe?g$/ would return all files whose name ends with .jpg or .jpeg
| fileSizeMin      | Number     | Search for files based on their size (in bytes).
| fileSizeMax      | Number     | Search for files based on their size (in bytes).
| dateBefore       | DatePicker | Only include messages before a given timestamp. The value this filter is applied to is the Date: header of the message which refers to the time the message is sent from the origin.
| dateAfter        | DatePicker | Only include messages after a given timestamp. The value this filter is applied to is the Date: header of the message which refers to the time the message is sent from the origin.
| indexedBefore    | DatePicker | Only include messages indexed before a given timestamp. This is not the same as the date of the email, it is the time Context.IO indexed this message.
| indexedAfter     | DatePicker | Only include messages indexed after a given timestamp. This is not the same as the date of the email, it is the time Context.IO indexed this message.
| includeThreadSize| Select     | Set to `true` to include thread size in the result.
| includeBody      | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeHeaders   | Select     | Can be set to `false` (default), `true` or raw. If set to `true`, complete message headers, parsed into an array, are included in the results. If set to raw, the headers are also included but as a raw unparsed string. Since full original headers bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeFlags     | Select     | Set to `true` to include thread size in the result.
| bodyType         | String     | Used when includeBody is set to get only body parts of a given MIME-type (for example text/html).
| includeSource    | Select     | Set to 'true' to include message sources in the result. Since message sources must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| sortOrder        | Select     | The sort order of the returned results. OPtions - asc,desc.
| limit            | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset           | Number     | Start the list at this offset (zero-based).

## ContextIO.getMessage
Get an individual message based on a `messageId`. You can use either a `messageId` or `emailMessageId`

| Field            | Type       | Description
|------------------|------------|----------
| consumerKey      | credentials| The consumer key from your account.
| consumerSecret   | credentials| The consumer secret from your account.
| accountId        | String     | Unique id of an account.
| messageId        | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| includeThreadSize| Select     | Set to `true` to include thread size in the result.Options - only `true`.
| includeBody      | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.Options - only `true`.
| includeHeaders   | Select     | Can be set to `false` (default), `true` or raw. If set to `true`, complete message headers, parsed into an array, are included in the results. If set to raw, the headers are also included but as a raw unparsed string. Since full original headers bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.Options - true,false.
| includeFlags     | Select     | Set to `true` to include thread size in the result. Options - only 'true'.
| bodyType         | String     | Used when includeBody is set to get only body parts of a given MIME-type (for example text/html).
| includeSource    | Select     | Set to 'true' to include message sources in the result. Since message sources must be retrieved from the IMAP server, expect a performance hit when setting this parameter.

## ContextIO.getFolderMessages
Listing messages from a specific folder.Alterntively, you can also perform the following call by source. This call bypases our cache of the account, so expect a performance hit.

| Field            | Type       | Description
|------------------|------------|----------
| consumerKey      | credentials| The consumer key from your account.
| consumerSecret   | credentials| The consumer secret from your account.
| accountId        | String     | Unique id of an account.
| sourceLabel        | String     | The label property of the source instance. You can use 0 as an alias for the first source of an account.
| folder        | String     |  The full folder path using / as the path hierarchy delimiter.
| includeThreadSize| Select     | Set to `true` to include thread size in the result.
| includeBody      | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeHeaders   | Select     | Can be set to `false` (default), `true` or raw. If set to `true`, complete message headers, parsed into an array, are included in the results. If set to raw, the headers are also included but as a raw unparsed string. Since full original headers bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeFlags     | Select     | Set to `true` to include thread size in the result.
| bodyType         | String     | Used when includeBody is set to get only body parts of a given MIME-type (for example text/html).
| flagSeen      | Select     | Message has been read. Set this parameter to `set` to set the flag, `unset` to unset it.
| limit            | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset           | Number     | Start the list at this offset (zero-based).


## ContextIO.getMessageBody
The name of a source in Context.IO is called a “label”. The label will be included in the response when you get account or source details and looks something like `email::provider`.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| type          | Select     | Many emails are sent with both rich text and plain text versions in the message body and by default, the response of this call will include both.

## ContextIO.getMessageFlags
This call lists all flags currently applied to this message.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.

## ContextIO.updateMessageFlags
This call will allow you to set or unset flags for a specific message.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| seen          | Select     | Message has been read. Set this parameter to `set` to set the flag, `unset` to unset it.
| answered      | Select     | Message has been answered. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagged       | Select     | Message is “flagged” for urgent/special attention. Set this parameter to `set` to set the flag, `unset` to unset it.
| deleted       | Select     | Message is `deleted` for later removal. An alternative way of deleting messages is to move it to the Trash folder. Set this parameter to `set` to set the flag, `unset` to unset it.
| draft         | Select     | Message has not completed composition (marked as a draft). Set this parameter to `set` to set the flag, `unset` to unset it.

## ContextIO.getMessageFolders
This call returns the folder(s) a message appears in.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.

## ContextIO.getMessageHeaders
This call returns only the headers for this message. We send a parsed response by default, but you can also choose to get the raw RFC 822 headers.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| raw           | Select     | By default, this returns messages headers parsed into an array. Set this parameter to `unparsed` to get raw unparsed headers.

## ContextIO.getMessageRawSource
This endpoint returns the raw RFC 822 source message.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.

## ContextIO.getMessageThread
This endpoint returns the the entire thread of messages a message is in that Context.IO has access to. Replies in the thread from accounts not in Context.Io would not appear here.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| includeBody   | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeHeaders| Select     | Can be set to `false` (default), `true` or raw. If set to `true`, complete message headers, parsed into an array, are included in the results. If set to raw, the headers are also included but as a raw unparsed string. Since full original headers bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| includeFlags  | Select     | Set to `true` to include thread size in the result.
| bodyType      | String     | Used when includeBody is set to get only body parts of a given MIME-type (for example text/html).
| includeSource | Select     | Set to 1 to include message sources in the result. Since message sources must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| sortOrder     | Select     | The sort order of the returned results.
| limit         | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset        | Number     | Start the list at this offset (zero-based).

## ContextIO.updateMessage
This call allows you to copy or move a message between folders, or also change the message’s flags all at once. If there are more than one source on the account, you can use this call to copy/move the message between these sources. 

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| folderToCopied| String     | The folder within dst_source the message should be copied to.
| sourceLabel   | String     | Label of the source you want the message copied to. This field is required if you’re moving a message that already exists in one source of the account to another source of that account.If you only want to move the message to a different folder within the same source, folderToCopied is sufficient.
| move          | Select     | By default, this calls copies the original message in the destination. Set this parameter to `true` to move instead of copy.
| flagSeen      | Select     | Message has been read. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagAnswered  | Select     | Message has been answered. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagDeleted   | Select     | Message is `deleted` for later removal. An alternative way of deleting messages is to move it to the Trash folder. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagDraft     | Select     | Message has not completed composition (marked as a draft). Set this parameter to `set` to set the flag, `unset` to unset it.

## ContextIO.moveMessage
This call allows you to move a message between folders, or also change the message’s flags all at once. If there are more than one source on the account, you can use this call to copy/move the message between these sources. 

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| messageId     | String     | Unique id of a message. This can be the `message_id` or `emailMessageId` property of the message. The `gmailMessageId` (prefixed with gm-) can also be used.
| folderToCopied| String     | The folder within dst_source the message should be copied to.
| sourceLabel   | String     | Label of the source you want the message copied to. This field is required if you’re moving a message that already exists in one source of the account to another source of that account.If you only want to move the message to a different folder within the same source, folderToCopied is sufficient.
| flagSeen      | Select     | Message has been read. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagAnswered  | Select     | Message has been answered. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagDeleted   | Select     | Message is `deleted` for later removal. An alternative way of deleting messages is to move it to the Trash folder. Set this parameter to `set` to set the flag, `unset` to unset it.
| flagDraft     | Select     | Message has not completed composition (marked as a draft). Set this parameter to `set` to set the flag, `unset` to unset it.

## ContextIO.getAllSourceFolders
List folders for a given source (email address).

| Field                | Type       | Description
|----------------------|------------|----------
| consumerKey          | credentials| The consumer key from your account.
| consumerSecret       | credentials| The consumer secret from your account.
| accountId            | String     | Unique id of an account.
| sourceLabel          | String     | The label property of the source instance. You can use 0 as an alias for the first source of an account.
| includeExtendedCounts| Select     | Set to `include` to include extended counts in the result (for now, the only extended count supported is number of unseen messages). Since counts must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| noCache              | Select     | Set to `true` to fetch the folder list directly from the IMAP server. Since data must be retrieved from the IMAP server, expect a performance hit when setting this parameter.

## ContextIO.getSourceFolder
Get information about a given folder such as number of messages, number of unseen messages, and other attributes.

| Field                | Type       | Description
|----------------------|------------|----------
| consumerKey          | credentials| The consumer key from your account.
| consumerSecret       | credentials| The consumer secret from your account.
| accountId            | String     | Unique id of an account.
| sourceLabel          | String     | The label property of the source instance. You can use 0 as an alias for the first source of an account.
| folder               | String     | The full folder path using / as the path hierarchy delimiter.
| delimiter            | String     | If / isn’t fancy enough as a hierarchy delimiter when specifying the folder you want to obtain, you’re free to use what you want, just make sure you set this delim parameter to tell us what you’re using.
| includeExtendedCounts| Select     | Set to `include` to include extended counts in the result (for now, the only extended count supported is number of unseen messages). Since counts must be retrieved from the IMAP server, expect a performance hit when setting this parameter.

## ContextIO.createUserLevelWebhook
Webhooks set at the user level are applicable only to the user on which the webhook is set. User level webhooks should be used for cases when you will be monitoring things that are very specific to each individual user.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| accountId          | String     | Unique id of an account.
| callbackUrl        | String     | A valid URL Context.IO calls when a matching message is found. The callback URL is called with an HTTP POST with message information in request body
| failureNotifyUrl   | String     | (DEPRECATED) A valid URL Context.IO calls if the WebHooks fails and will no longer be active. That may happen if, for example, the server becomes unreachable or if it closes an IDLE connection and we can’t re-establish it.
| filterTo           | List       | Check for new messages received to a given name or email address. Also accepts a comma delimited list of email addresses.
| filterFrom         | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterEmailList    | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterSubject      | String     | Check for new messages with a subject matching a given string or regular expression.
| filterThread       | String     | Check for new messages in a given thread. Value can be a gmail_thread_id or the email_message_id of an existing message currently in the thread.
| filterFileName     | String     | Check for new messages where a file whose name matches the given string is attached. 
| filterFolderAdded  | String     | Check for messages filed in a given folder. On Gmail, this is equivalent to having a label applied to a message. The value should be the complete name (including parents if applicable) of the folder you want to track. 
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| filterToDomains    | List       | Check for new messages sent to a given domain. Also accepts a comma delimited list of domains.
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| includeBody        | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| bodyType           | Select     | Required to be set when “include_body” is set to get only body parts of a given MIME-type
| includeHeader      | Select     | Set to `true` or raw to include message headers in the result.
| receiveAllChanges  | Select     | By default, we only send a webhook notification on the first event of a message (i.e. when a message is received or sent). Subsequent changes of a specific message do not trigger a webhook (i.e. if a message changes folders). When this parameter is set to `true`, we will send all events related to a message.
| receiveDrafts      | Select     | Set to `true`, you will receive messages that are flagged as 'Drafts' in Gmail

## ContextIO.getAllUserWebhooks
List all webhooks set on a specific user.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.

## ContextIO.getUserWebhookDetail
Get information about a single user level webhook, such whether it is active and what filters are set on the webhook.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| webhookId     | String     | Unique id of a user level webhook.

## ContextIO.updateUserLevelWebhook
Webhooks set at the user level are applicable only to the user on which the webhook is set. User level webhooks should be used for cases when you will be monitoring things that are very specific to each individual user.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| accountId          | String     | Unique id of an account.
| webhookId          | String     | Unique id of a user level webhook.
| callbackUrl        | String     | A valid URL Context.IO calls when a matching message is found. The callback URL is called with an HTTP POST with message information in request body
| failureNotifyUrl   | String     | (DEPRECATED) A valid URL Context.IO calls if the WebHooks fails and will no longer be active. That may happen if, for example, the server becomes unreachable or if it closes an IDLE connection and we can’t re-establish it.
| active             | Select     | By default, webhooks are set to active when first created. Set to 'false' to temporarily stop receiving callbacks for this webhook. Set to `true` again to resume.
| filterTo           | List       | Check for new messages received to a given name or email address. Also accepts a comma delimited list of email addresses.
| filterFrom         | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterEmailList    | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterSubject      | String     | Check for new messages with a subject matching a given string or regular expression.
| filterThread       | String     | Check for new messages in a given thread. Value can be a gmail_thread_id or the email_message_id of an existing message currently in the thread.
| filterFileName     | String     | Check for new messages where a file whose name matches the given string is attached. 
| filterFolderAdded  | String     | Check for messages filed in a given folder. On Gmail, this is equivalent to having a label applied to a message. The value should be the complete name (including parents if applicable) of the folder you want to track. 
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| filterToDomains    | List       | Check for new messages sent to a given domain. Also accepts a comma delimited list of domains.
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| includeBody        | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| bodyType           | Select     | Required to be set when “include_body” is set to get only body parts of a given MIME-type
| includeHeader      | Select     | Set to `true` or raw to include message headers in the result.
| receiveAllChanges  | Select     | By default, we only send a webhook notification on the first event of a message (i.e. when a message is received or sent). Subsequent changes of a specific message do not trigger a webhook (i.e. if a message changes folders). When this parameter is set to `true`, we will send all events related to a message.
| receiveDrafts      | Select     | Set to `true`, you will receive messages that are flagged as `Drafts` in Gmail

## ContextIO.deleteUserLevelWebhook
Delete a specific user level webhook.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| webhookId     | String     | Unique id of a user level webhook.

## ContextIO.createApplicationLevelWebhook
Webhooks set at the application level apply to all users in your userbase.Application level webhooks encourage a more DRY approach to using the Context.IO API.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| callbackUrl        | String     | A valid URL Context.IO calls when a matching message is found. The callback URL is called with an HTTP POST with message information in request body
| failureNotifyUrl   | String     | (DEPRECATED) A valid URL Context.IO calls if the WebHooks fails and will no longer be active. That may happen if, for example, the server becomes unreachable or if it closes an IDLE connection and we can’t re-establish it.
| active             | Select     | By default, webhooks are set to active when first created. Set to 'false' to temporarily stop receiving callbacks for this webhook. Set to `true` again to resume.
| filterTo           | List       | Check for new messages received to a given name or email address. Also accepts a comma delimited list of email addresses.
| filterFrom         | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterEmailList    | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterSubject      | String     | Check for new messages with a subject matching a given string or regular expression.
| filterThread       | String     | Check for new messages in a given thread. Value can be a gmail_thread_id or the email_message_id of an existing message currently in the thread.
| filterFileName     | String     | Check for new messages where a file whose name matches the given string is attached. 
| filterFolderAdded  | String     | Check for messages filed in a given folder. On Gmail, this is equivalent to having a label applied to a message. The value should be the complete name (including parents if applicable) of the folder you want to track. 
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| filterToDomains    | List       | Check for new messages sent to a given domain. Also accepts a comma delimited list of domains.
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| includeBody        | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| bodyType           | Select     | Required to be set when “include_body” is set to get only body parts of a given MIME-type
| includeHeader      | Select     | Set to `true` or raw to include message headers in the result.
| receiveAllChanges  | Select     | By default, we only send a webhook notification on the first event of a message (i.e. when a message is received or sent). Subsequent changes of a specific message do not trigger a webhook (i.e. if a message changes folders). When this parameter is set to `true`, we will send all events related to a message.
| receiveDrafts      | Select     | Set to `true`, you will receive messages that are flagged as `Drafts` in Gmail
| receiveHistorical  | Select     | Set to `true`, we will perform a backscan of an account when a new account is added and send messages from new to old to your callback URL.

## ContextIO.getAllApplicationWebhooks
List all application level webhooks.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.

## ContextIO.getApplicationWebhookDetails
Get information about a single application level webhook, such whether it is active and what filters are set on the webhook.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| webhookId     | String     | Unique id of a user level webhook.

## ContextIO.updateApplicationLevelWebhook
Edit an existing webhook. Please note changes to an existing webhook are not appended but overwritten.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| webhookId          | String     | Unique id of a user level webhook.
| callbackUrl        | String     | A valid URL Context.IO calls when a matching message is found. The callback URL is called with an HTTP POST with message information in request body
| failureNotifyUrl   | String     | (DEPRECATED) A valid URL Context.IO calls if the WebHooks fails and will no longer be active. That may happen if, for example, the server becomes unreachable or if it closes an IDLE connection and we can’t re-establish it.
| active             | Select     | By default, webhooks are set to active when first created. Set to 'false' to temporarily stop receiving callbacks for this webhook. Set to `true` again to resume.
| filterTo           | List       | Check for new messages received to a given name or email address. Also accepts a comma delimited list of email addresses.
| filterFrom         | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterEmailList    | List       | Check for new messages received from a given name or email address. Also accepts a comma delimited list of email addresses.
| filterSubject      | String     | Check for new messages with a subject matching a given string or regular expression.
| filterThread       | String     | Check for new messages in a given thread. Value can be a gmail_thread_id or the email_message_id of an existing message currently in the thread.
| filterFileName     | String     | Check for new messages where a file whose name matches the given string is attached. 
| filterFolderAdded  | String     | Check for messages filed in a given folder. On Gmail, this is equivalent to having a label applied to a message. The value should be the complete name (including parents if applicable) of the folder you want to track. 
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| filterToDomains    | List       | Check for new messages sent to a given domain. Also accepts a comma delimited list of domains.
| filterFolderRemoved| String     | Check for messages removed from a given folder. On Gmail, this is equivalent to having a label removed from a message. The value should be the complete name (including parents if applicable) of the folder you want to track.
| includeBody        | Select     | Set to `true` to include message bodies in the result. Since message bodies must be retrieved from the IMAP server, expect a performance hit when setting this parameter.
| bodyType           | Select     | Required to be set when “include_body” is set to get only body parts of a given MIME-type
| includeHeader      | Select     | Set to `true` or raw to include message headers in the result.
| receiveAllChanges  | Select     | By default, we only send a webhook notification on the first event of a message (i.e. when a message is received or sent). Subsequent changes of a specific message do not trigger a webhook (i.e. if a message changes folders). When this parameter is set to `true`, we will send all events related to a message.
| receiveDrafts      | Select     | Set to `true`, you will receive messages that are flagged as `Drafts` in Gmail
| receiveHistorical  | Select     | Set to `true`, we will perform a backscan of an account when a new account is added and send messages from new to old to your callback URL.

## ContextIO.deleteApplicationLevelWebhook
Delete a specific user level webhook.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| webhookId     | String     | Unique id of a user level webhook.

## ContextIO.getAllContacts
There are several ways you can interact with a user’s contacts.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| search        | String     | String identifying the name or the email address of the contact(s) you are looking for.
| activeBefore  | DatePicker | Only include contacts included in at least one email dated before a given time. 
| activeAfter   | DatePicker | Only include contacts included in at least one email dated after a given time.
| sortBy        | Select     | The field by which to sort the returned results.
| sortOrder     | Select     | The sort order of the returned results.
| limit         | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset        | Number     | Start the list at this offset (zero-based).

## ContextIO.getContactDetails
Get information about a given contact such as when a message was last sent or last received, as well as how many messages have been sent to this contact, or received from this contact.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| email         | String     | Email address of a contact

## ContextIO.getFilesSharedWithContact
List files shared with a specific contact(s).

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| email         | String     | Email address of a contact
| limit         | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset        | Number     | Start the list at this offset (zero-based).

## ContextIO.getListMessagesExchange
Get a list of email messages exchanged with one or more email addresses. This includes messages sent by a contact, received from a contact, or where both the contact and mailbox owner are included in the list of recipients.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| email         | String     | Email address of a contact.
| limit         | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset        | Number     | Start the list at this offset (zero-based).

## ContextIO.getListThreadsIncludeContact
List threads where a specific contact(s) is present in the recipient list.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| email         | String     | Email address of a contact.
| limit         | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset        | Number     | Start the list at this offset (zero-based).

## ContextIO.getAllFiles
The name of a source in Context.IO is called a “label”. The label will be included in the response when you get account or source details and looks something like `email::provider`.

| Field            | Type       | Description
|------------------|------------|----------
| consumerKey      | credentials| The consumer key from your account.
| consumerSecret   | credentials| The consumer secret from your account.
| accountId        | String     | Unique id of an account.
| fileName         | String     | Search for files based on their name. You can filter names using typical shell wildcards such as *, ? and [] or regular expressions by enclosing the search expression in a leading / and trailing /. For example, *.pdf would give you all PDF files while /.jpe?g$/ would return all files whose name ends with .jpg or .jpeg
| fileSizeMin      | Number     | Search for files based on their size (in bytes).
| fileSizeMax      | Number     | Search for files based on their size (in bytes).
| email            | String     | Email address of the contact for whom you want the latest files in which this email address was listed as a recipient.
| to               | String     | Email address of a contact messages have been sent to.
| from             | String     | Email address of a contact messages have been received from.
| emailOfContactCC | String     | Email address of a contact CC'ed on the messages.
| emailOfContactBCC| String     | Email address of a contact BCC'ed on the messages.
| dateBefore       | DatePicker | Only include messages before a given timestamp. The value this filter is applied to is the Date: header of the message which refers to the time the message is sent from the origin.
| dateAfter        | DatePicker | Only include messages after a given timestamp. The value this filter is applied to is the Date: header of the message which refers to the time the message is sent from the origin.
| indexedBefore    | DatePicker | Only include messages indexed before a given timestamp. This is not the same as the date of the email, it is the time Context.IO indexed this message.
| indexedAfter     | DatePicker | Only include messages indexed after a given timestamp. This is not the same as the date of the email, it is the time Context.IO indexed this message.
| source           | String     | Filter messages by the account source label.
| sortOrder        | Select     | The sort order of the returned results.
| limit            | Number     | The maximum number of results to return. The maximum limit is 100. The default if no limit is provided is 25.
| offset           | Number     | Start the list at this offset (zero-based).

## ContextIO.getFileDetails
Get details about a specific file.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| fileId        | String     | Unique id of a file.

## ContextIO.downloadFilesContents
There are a couple ways you can download the file itself from the API.Get the file itself in the response;Get an AWS link you can use to download the file;

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| accountId     | String     | Unique id of an account.
| fileId        | String     | Unique id of a file.
| asLink        | Select     | Set to `true`, get an AWS link you can use to download the file.

## ContextIO.discoveringImapSettings
If you prefer to handle your own authentication and add accounts or sources manually, you may want to check for IMAP configuration settings to add users. The Discovery endpoint returns known IMAP configuration settings given an email address.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.
| sourceType    | Select     | The type of source you want to discover settings for.
| email         | String     | An email address you want to discover IMAP settings for.

## ContextIO.getAllOauthProviders
List all oauth providers linked to your Context.IO API key.

| Field         | Type       | Description
|---------------|------------|----------
| consumerKey   | credentials| The consumer key from your account.
| consumerSecret| credentials| The consumer secret from your account.

## ContextIO.createOauthProvider
Add an oauth provider.

| Field                 | Type       | Description
|-----------------------|------------|----------
| consumerKey           | credentials| The consumer key from your account.
| consumerSecret        | credentials| The consumer secret from your account.
| type                  | Select     | Identification of the OAuth2 provider.
| providerConsumerKey   | String     | The OAuth2 Client ID.
| providerConsumerSecret| String     | The OAuth2 Client Secret

## ContextIO.getOauthProviderDetails
Get information about an oauth provider.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| providerConsumerKey| String     | The consumer key for this external OAuth provider

## ContextIO.deleteOauthProvider
Delete an oauth provider.

| Field              | Type       | Description
|--------------------|------------|----------
| consumerKey        | credentials| The consumer key from your account.
| consumerSecret     | credentials| The consumer secret from your account.
| providerConsumerKey| String     | The consumer key for this external OAuth provider

