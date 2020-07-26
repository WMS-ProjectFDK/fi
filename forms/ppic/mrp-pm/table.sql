SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tableplan_used](
	[ID] [int] IDENTITY(1,1) NOT NULL,
	[lower_item_no] [int] NULL,
	[mps_date] [date] NULL,
	[qty_p] [bigint] NULL,
	[flag] [int] NULL
) ON [PRIMARY]
GO
