// by : Danar
// at : 6:05 AM Sunday, May 17, 2015
// reason : apply End Date to table master project

ALTER TABLE `amtsproject` ADD `EndDate` DATE NULL AFTER `StartDate`;
