SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[temptable2](
	[item_no] [int] NULL,
	[rata1] [decimal](18, 2) NULL,
	[rata2] [decimal](18, 2) NULL,
	[rata3] [decimal](18, 2) NULL,
	[rata4] [decimal](18, 2) NULL,
	[inventory] [bigint] NULL,
	[outstanding] [bigint] NULL
) ON [PRIMARY]
GO


SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[temptable](
	[item_no] [int] NULL,
	[unit] [nvarchar](10) NULL,
	[deskripsi] [nvarchar](100) NULL,
	[item_type] [nvarchar](100) NULL,
	[min_days] [int] NULL,
	[max_days] [int] NULL
) ON [PRIMARY]
GO
