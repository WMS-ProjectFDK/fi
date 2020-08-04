<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Public Class Menus
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing AndAlso components IsNot Nothing Then
            components.Dispose()
        End If
        MyBase.Dispose(disposing)
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer
    Private mainMenu1 As System.Windows.Forms.MainMenu

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.mainMenu1 = New System.Windows.Forms.MainMenu
        Me.Button1 = New System.Windows.Forms.Button
        Me.Button2 = New System.Windows.Forms.Button
        Me.Button3 = New System.Windows.Forms.Button
        Me.WebBrowser1 = New System.Windows.Forms.WebBrowser
        Me.SuspendLayout()
        '
        'Button1
        '
        Me.Button1.Font = New System.Drawing.Font("Tahoma", 30.0!, System.Drawing.FontStyle.Regular)
        Me.Button1.Location = New System.Drawing.Point(108, 66)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(301, 81)
        Me.Button1.TabIndex = 2
        Me.Button1.Text = "Scan"
        '
        'Button2
        '
        Me.Button2.Font = New System.Drawing.Font("Tahoma", 30.0!, System.Drawing.FontStyle.Regular)
        Me.Button2.Location = New System.Drawing.Point(108, 170)
        Me.Button2.Name = "Button2"
        Me.Button2.Size = New System.Drawing.Size(301, 74)
        Me.Button2.TabIndex = 3
        Me.Button2.Text = "Upload Data"
        '
        'Button3
        '
        Me.Button3.Font = New System.Drawing.Font("Tahoma", 30.0!, System.Drawing.FontStyle.Regular)
        Me.Button3.Location = New System.Drawing.Point(108, 284)
        Me.Button3.Name = "Button3"
        Me.Button3.Size = New System.Drawing.Size(301, 60)
        Me.Button3.TabIndex = 4
        Me.Button3.Text = "Exit"
        '
        'WebBrowser1
        '
        Me.WebBrowser1.Location = New System.Drawing.Point(15, 353)
        Me.WebBrowser1.Name = "WebBrowser1"
        Me.WebBrowser1.Size = New System.Drawing.Size(517, 200)
        '
        'Menus
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(96.0!, 96.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi
        Me.AutoScroll = True
        Me.ClientSize = New System.Drawing.Size(562, 594)
        Me.Controls.Add(Me.WebBrowser1)
        Me.Controls.Add(Me.Button3)
        Me.Controls.Add(Me.Button2)
        Me.Controls.Add(Me.Button1)
        Me.Menu = Me.mainMenu1
        Me.Name = "Menus"
        Me.Text = "Menu"
        Me.ResumeLayout(False)

    End Sub
    Friend WithEvents Button1 As System.Windows.Forms.Button
    Friend WithEvents Button2 As System.Windows.Forms.Button
    Friend WithEvents Button3 As System.Windows.Forms.Button
    Friend WithEvents WebBrowser1 As System.Windows.Forms.WebBrowser
End Class
