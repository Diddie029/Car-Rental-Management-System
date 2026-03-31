-- Add this SQL code to your database to create the messages table

CREATE TABLE IF NOT EXISTS `tblmessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userEmail` varchar(100) DEFAULT NULL,
  `bookingId` int(11) DEFAULT NULL,
  `vehicleId` int(11) DEFAULT NULL,
  `messageTitle` varchar(255) DEFAULT NULL,
  `messageBody` longtext,
  `messageType` varchar(50) DEFAULT 'booking_update',
  `status` int(11) DEFAULT 0 COMMENT '0=unread, 1=read',
  `createdDate` timestamp DEFAULT CURRENT_TIMESTAMP,
  `isArchived` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `userEmail` (`userEmail`),
  KEY `bookingId` (`bookingId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- This table stores messages for users about:
-- 1. Booking request approvals/rejections
-- 2. System notifications
-- 3. Car rental updates
-- messageType can be: 'booking_approved', 'booking_rejected', 'booking_pending', 'payment_received', 'car_ready', etc.
